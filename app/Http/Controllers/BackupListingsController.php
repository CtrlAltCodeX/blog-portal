<?php

namespace App\Http\Controllers;

use App\Exports\BackupListingsExport;
use App\Models\BackupEmail;
use App\Models\BackupListing;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class BackupListingsController extends Controller
{
    /**
     * Initiate the class instance
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Index function
     *
     * @return \Illuminate\View\View
     */
    public function backupListings()
    {
        $getAllListings = BackupListing::all();

        return view('backup-listings.index', compact('getAllListings'));
    }

    /**
     * Export Table
     *
     * @return void
     */
    public function exportData()
    {
        $getAllListings = BackupListing::all()->makeHidden(['id', 'created_at', 'updated_at'])->toArray();

        $mainColums = [
            'Product id',
            'Title',
            'Description',
            'MRP',
            'Selling Price',
            'Publisher',
            'Author Name',
            'Edition',
            'Categories',
            'SKU',
            'language',
            'No of Pages',
            'Condition',
            'Binding Type',
            'Insta Mojo URL',
            'Base URL'
        ];

        array_unshift($getAllListings, $mainColums);

        return $getAllListings;
    }

    /**
     * Generate Merchant File
     *
     * @return array
     */
    public function getMerchantExportFile()
    {
        $getAllListings = [];

        $getAllListingsFromDatabase = BackupListing::all()->makeHidden(['id', 'created_at', 'updated_at'])
            ->toArray();

        foreach ($getAllListingsFromDatabase as $key => $listing) {
            $getAllListings[$key][] = $listing['title'];
            $getAllListings[$key][] = $listing['product_id'];
            $getAllListings[$key][] = $listing['selling_price'];
            $getAllListings[$key][] = '0';
            $getAllListings[$key][] = '0';
            $getAllListings[$key][] = $listing['condition'];
            $getAllListings[$key][] = 'In Stock';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = 'en';
            $getAllListings[$key][] = $listing['publisher'];
            $getAllListings[$key][] = $listing['description'];
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = $listing['base_url'];
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = 'IN';
        }

        $mainColums = [
            'title',
            'id',
            'price',
            'clicks',
            'unpaid clicks',
            'condition',
            'availability',
            'Free listings - disapproved or invalid',
            'channel',
            'feed label',
            'language',
            'brand',
            'description',
            'identifier exists',
            'image link',
            'pause',
            'shipping(country)',
        ];

        array_unshift($getAllListings, $mainColums);

        return $getAllListings;
    }

    /**
     * Generate Facebook Pixel File
     *
     * @return array
     */
    public function getFacebookExportFile()
    {
        $getAllListings = [];

        $getAllListingsFromDatabase = BackupListing::all()->makeHidden(['id', 'created_at', 'updated_at'])
            ->toArray();

        foreach ($getAllListingsFromDatabase as $key => $listing) {
            $getAllListings[$key][] = $listing['product_id'];
            $getAllListings[$key][] = $listing['title'];
            $getAllListings[$key][] = $listing['description'];
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = $listing['condition'];
            $getAllListings[$key][] = $listing['mrp'];
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = $listing['base_url'];
            $getAllListings[$key][] = $listing['publisher'];
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = $listing['selling_price'];
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
        }

        $mainColums = [
            'id',
            'title',
            'description',
            'availability',
            'condition',
            'price',
            'link',
            'image link',
            'brand',
            'google_product_category',
            'fb_product_category',
            'quantity_to_sell_on_facebook',
            'sale_price',
            'sale_price_effective_date',
            'item_group_id',
            'gender',
            'color',
            'size',
            'age_group',
            'material',
            'pattern',
            'shipping',
            'shipping_weight',
            'style[0]'
        ];

        array_unshift($getAllListings, $mainColums);

        return $getAllListings;
    }

    /**
     * Download Excel
     *
     * @return void
     */
    public function downloadExcel()
    {
        if (request()->file && request()->type == 'google') {
            return Excel::download(new BackupListingsExport($this->getMerchantExportFile()), 'merchant-file.tsv', \Maatwebsite\Excel\Excel::TSV);
        } else if (request()->file && request()->type == 'facebook') {
            return Excel::download(new BackupListingsExport($this->getFacebookExportFile()), 'facebook-file.xlsx');
        } else if (request()->file && request()->type == 'report') {
            return Excel::download(new BackupListingsExport($this->exportData()), 'report.xlsx');
        }
    }

    /**
     * Read Log and Display
     *
     * @return void
     */
    public function getLoggerFile()
    {
        $logFilePath = storage_path('logs/backup_activity.log');

        $logContent = 'No Such Logs Exist';
        if (File::exists($logFilePath)) $logContent = File::get($logFilePath);

        $logContent = str_ireplace("local.INFO", '', $logContent);

        return view('settings.backup-logs', compact('logContent'));
    }

    /**
     * Insert email for Backup
     *
     * @return void
     */
    public function backupEmail()
    {
        $backupMails = BackupEmail::all();

        return view('settings.backup-email', compact('backupMails'));
    }

    /**
     * Save & update email for Backup
     *
     * @return void
     */
    public function saveEmail()
    {
        try {
            $email = request()->email;
            $name = request()->name;
            $update = request()->update;

            if ((isset($update) && $existEmail = BackupEmail::find($update))) {
                $existEmail->update([
                    'email' => $email,
                    'name' => $name
                ]);
            } else {
                $this->validate(request(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:backup_emails,email',
                ]);

                BackupEmail::create([
                    'name' => $name,
                    'email' => $email
                ]);
            }

            session()->flash('success', 'Emails updated successfully');

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', 'Something went Wrong!!');

            return redirect()->back();
        }
    }

    /**
     * Delete Email Successfully
     *
     * @param int $id
     * @return void
     */
    public function deleteEmail($id)
    {
        BackupEmail::find($id)->delete();

        session()->flash('success', 'Emails delete successfully');

        return redirect()->back();
    }

    /**
     * Manually Run Backup
     *
     * @return void
     */
    public function manuallyRunBackup()
    {
        try {
            Artisan::call('backup:listing'); // Replace 'command:name' with your actual command signature
            $output = Artisan::output(); // Get the output of the command if needed

            session()->flash('success', 'Backup Done Successfully');

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->back();
        }
    }
}
