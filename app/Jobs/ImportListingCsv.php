<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Listing;
use App\Models\UserListingInfo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;

class ImportListingCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(protected $data, protected $user, protected $postID, protected $key) {}

    /**
     * Execute the job.
     */
    public function handle()
    {
        $images = [];
        if ($this->data->images) {
            $images[] = $this->downloadImage($this->data->images);
        }

        $data = [
            'title' => $this->data->title,
            'description' => $this->data->description,
            'mrp' => ($this->data->mrp != 'Not found') ? $this->data->mrp : null,
            'selling_price' => ($this->data->selling_price != 'Not found') ? $this->data->selling_price : null,
            'publisher' => $this->data->publisher,
            'author_name' => $this->data->author_name,
            'edition' => $this->data->edition,
            'categories' => null,
            'sku' => $this->data->sku ?? null,
            'language' => $this->data->language,
            'no_of_pages' => $this->data->no_of_pages,
            'condition' => $this->data->condition ?? null,
            'binding_type' => $this->data->binding ?? null,
            'insta_mojo_url' => $this->data->url ?? null,
            'images' => count($images) ? json_encode([$images]) : null,
            'multiple_images' => $this->data->multiple_images ?? null,
            'status' => 0,
            'created_by' => $this->user,
            'is_bulk_upload' => 1,
            'product_id' => $this->postID ? $this->postID : null,
            'isbn_10' => $this->data->ISBN_10 ?? null,
            'isbn_13' => $this->data->ISBN_13 ?? null,
            'condition' => $this->data->condition ?? null,
            'binding' => $this->data->binding_type ?? null,
            'weight' => $this->data->weight ?? null,
            'reading_age' => $this->data->reading_age ?? null,
            'country_origin' => $this->data->country_origin ?? null,
            'genre' => $this->data->genre ?? null,
            'manufacturer' => $this->data->manufacturer ?? null,
            'importer' => $this->data->importer ?? null,
            'packer' => $this->data->packer ?? null,
        ];

        $listing = Listing::create($data);

        UserListingInfo::create([
            'image' => $this->data->images,
            'title' => $this->data->title,
            'created_by' => $this->user,
            'approved_by' => null,
            'approved_at' => null,
            'status' => 0,
            'status_listing' => 'Created',
            'listings_id' => $listing->id,
        ]);

        if (!$listing) return false;

        return true;
    }

    public function downloadImage($imageURL)
    {
        $response = Http::withOptions(['verify' => false])
            ->get($imageURL);

        if ($response->successful()) {
            $filename = basename($this->key . "_" . time() . '.jpg'); // Get the filename from the URL
            $path = 'images/' . $filename; // Define the storage path
            Storage::disk('public')->put($path, $response->body());

            $background = (new ImageManager())->canvas(555, 555, '#ffffff');

            $background->insert(Image::make(storage_path('app/public/images/' . $filename))->resize(390, 520), 'center');

            $background->save(storage_path('app/public/images/' . $filename));

            return $path;
        }
    }
}
