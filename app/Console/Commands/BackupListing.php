<?php

namespace App\Console\Commands;

use App\Exports\BackupListingsExport;
use App\Exports\GoogleMerchantExport;
use App\Exports\ListingsExport;
use App\Mail\BackupMail;
use App\Models\BackupEmail;
use App\Models\BackupListing as ModelsBackupListing;
use App\Models\BackupListingImage;
use App\Models\BackupLogs;
use App\Models\SiteSetting;
use App\Services\GoogleService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class BackupListing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:listing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will insert the blogger data to Database and export file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $batchId = str_pad(0000001, 7, '0', STR_PAD_LEFT);
            if ($lastLog = BackupLogs::latest()->first()) {
                $batchId = str_pad(++$lastLog->batch_id, 7, '0', STR_PAD_LEFT);
            }

            // Log::channel('backup_activity_log')->info('Backup Stated - ');
            $started = now();
            $this->getAllProducts();
            // Log::channel('backup_activity_log')->info('Backup Completed - ');
            $completed = now();

            $listingData = app('App\Http\Controllers\BackupListingsController');

            $reportFileTime = now();
            $fileName = 'report-' . $reportFileTime . '.xlsx';
            Excel::store(new BackupListingsExport($listingData->exportData()), "/public/" . $fileName);
            // Log::channel('backup_activity_log')->info('Export File Downloaded - ');

            $merchantFileTime = now();
            $googleMerchantfileName = 'merchant-file' . $merchantFileTime . '.tsv';
            $singleGoogleMerchantfileName = 'merchant-file.tsv';
            Excel::store(new ListingsExport($listingData->getMerchantExportFile()), "/public/" . $googleMerchantfileName);
            Excel::store(new ListingsExport($listingData->getMerchantExportFile()), "/public/" . $singleGoogleMerchantfileName);
            // Log::channel('backup_activity_log')->info('Google Merchant File Downloaded - ');

            $facebookFileTime = now();
            $facebookPixelfileName = 'facebook-file' . $facebookFileTime . '.xlsx';
            $singleFacebookPixelfileName = 'facebook-file.xlsx';
            Excel::store(new BackupListingsExport($listingData->getFacebookExportFile()), "/public/" . $facebookPixelfileName);
            Excel::store(new BackupListingsExport($listingData->getFacebookExportFile()), "/public/" . $singleFacebookPixelfileName);
            // Log::channel('backup_activity_log')->info('Facebook Pixel File Downloaded - ');

            $sqlFileTime = now();
            $sqlfileName = 'export-file' . $sqlFileTime . '.sql';
            Storage::disk('public')->put($sqlfileName, $listingData->exportSQL()[1]);

            $zip = new ZipArchive;
            $zipFileName = 'backup-files' . now() . '.zip';
            if ($zip->open(storage_path($zipFileName), ZipArchive::CREATE) === true) {
                $zip->addFile(storage_path("app/public/" . $fileName), basename(storage_path("app/public/" . $fileName)));
                $zip->addFile(storage_path("app/public/" . $sqlfileName), basename(storage_path("app/public/" . $sqlfileName)));
            }

            // Close the zip archive
            // $zip->close();

            Storage::disk('dropbox')->put('files/' . $sqlfileName, $listingData->exportSQL()[1]);
            Storage::disk('dropbox')->put('files/' . $fileName, file_get_contents(storage_path('app/public/' . $fileName)));

            $allEmails = BackupEmail::all();
            $emailTo = [];
            foreach ($allEmails as $email) {
                Mail::to($email->email)->send(new BackupMail(storage_path($zipFileName)));
                // Log::channel('backup_activity_log')->info('Email Send Successfully to ' . $email->email . " - ");
                $emailTo[] = $email->email;
            }

            BackupLogs::create([
                'batch_id' => $batchId,
                'started' => $started,
                'completed' => $completed,
                'export_file' => $reportFileTime,
                'merchant_file' => $merchantFileTime,
                'facebook_file' => $facebookFileTime,
                'email_to' => implode(",", $emailTo),
                'sql_file' => $sqlFileTime,
            ]);
        } catch (\Exception $e) {
            Log::channel('backup_activity_log')->info('Facing some issues');

            Log::info($e);
        }
    }

    /**
     * Get all Products
     *
     * @param GoogleService $googleService
     * @return array
     */
    public function getAllProducts()
    {
        $url = $this->getSiteBaseUrl();
        $response = Http::get($url . '/feeds/posts/default?alt=json');
        $totalProducts =  $response->json()['feed']['openSearch$totalResults']['$t'];

        $paginate = 1;
        // for ($j = 0; $j < (int) ($totalProducts / 150); $j++) {
        for ($i = 0; $i <= 2; $i++) {
            $allProducts = app('App\Services\GoogleService')->backupToDatabse($paginate);

            foreach ($allProducts['paginator'] as $key => $products) {
                $images = [];
                $doc = new \DOMDocument();
                if (((array)($products->content))['$t']) {
                    @$doc->loadHTML(((array)($products->content))['$t']);
                }
                $td = $doc->getElementsByTagName('td');
                $a = $doc->getElementsByTagName('a');
                $div = $doc->getElementsByTagName('div');

                $price = explode('-', $td->item(1)->textContent ?? '');
                $selling = $price[0] ?? 0;
                $mrp = $price[1] ?? 0;
                $image = $doc->getElementsByTagName("img")?->item(0)?->getAttribute('src');
                $productId = explode('-', ((array)$allProducts['paginator'][$key]->id)['$t'])[2];
                $productTitle = ((array)$allProducts['paginator'][$key]->title)['$t'];
                $published = ((array)$allProducts['paginator'][$key]->published)['$t'];
                $updated = ((array)$allProducts['paginator'][$key]->updated)['$t'];

                $edition_author_lang = explode(',', $td->item(7)->textContent ?? '');
                $author_name = $edition_author_lang[0];
                $edition = $edition_author_lang[1] ?? '';
                $lang = $edition_author_lang[2] ?? '';

                $bindingType = explode(',', $td->item(9)->textContent ?? '');
                $binding = $bindingType[0] ?? '';
                $condition = $bindingType[1] ?? '';

                $page_no = $td->item(11)->textContent ?? '';

                $instaUrl = "";
                for ($i = 0; $i < $a->length; $i++) {
                    $item = trim($a->item($i)->textContent);
                    if ($item == 'BUY AT INSTAMOJO') {
                        $instaUrl = $a->item($i)->getAttribute('href');
                    }
                }

                $sku = '';
                $publication = '';
                for ($i = 0; $i < $td->length; $i++) {
                    if ($td->item($i)->getAttribute('itemprop') == 'sku') {
                        $sku = trim($td->item($i)->textContent);
                    }

                    if ($td->item($i)->getAttribute('itemprop') == 'color') {
                        $publication = trim($td->item($i)->textContent);
                    }
                }

                $desc = [];
                for ($i = 0; $i < $div->length; $i++) {
                    if ($div->item($i)->getAttribute('class') == 'pbl box dtmoredetail dt_content') {
                        $desc[] = str_replace("'", "", trim($div->item($i)->textContent));
                    }
                }

                if ($doc->getElementsByTagName("img")->length > 1) {
                    for ($i = 0; $i < $doc->getElementsByTagName("img")->length; $i++) {
                        $imageElement = $doc->getElementsByTagName("img")->item($i);
                        $images[] = $imageElement->getAttribute('src');
                    }
                }

                $productTitle = str_replace("'", "", trim($productTitle));
                $allInfo = [
                    'product_id' => trim($productId),
                    'title' => trim($productTitle),
                    'description' => $desc[0] ?? '',
                    'mrp' => (int) trim($mrp),
                    'selling_price' => (int) trim($selling),
                    'publisher' => trim($publication),
                    'author_name' => trim($author_name),
                    'edition' => trim($edition),
                    'categories' => json_encode(collect($products->category ?? [])->pluck('term')->toArray()),
                    'sku' => trim($sku),
                    'language' => trim($lang),
                    'no_of_pages' => trim($page_no),
                    'binding_type' => trim($binding),
                    'condition' => trim($condition),
                    'insta_mojo_url' => trim($instaUrl),
                    'base_url' => $image ?? '',
                    'multiple' => $images,
                    'url' => $products->link[4]->href
                ];

                if ($product = ModelsBackupListing::where("product_id", $productId)->first()) {
                    $product->update($allInfo);

                    if ($additionalImage = BackupListingImage::find($product->id)) {
                        foreach ($allInfo['multiple'] as $imageUrl) {
                            $additionalImage->update([
                                'image_url' => $imageUrl,
                            ]);
                        }
                    }
                } else {
                    $listing = ModelsBackupListing::create($allInfo);

                    foreach ($allInfo['multiple'] as $imageUrl) {
                        BackupListingImage::create([
                            'listing_id' => $listing->id,
                            'image_url' => $imageUrl,
                        ]);
                    }
                }
            }

            $paginate = $paginate + 150;
        }
    }

    /**
     * Get Site Base URL
     *
     * @return void
     */
    public function getSiteBaseUrl()
    {
        $siteSetting = SiteSetting::first();

        if ($siteSetting) return $siteSetting->url;
    }
}
