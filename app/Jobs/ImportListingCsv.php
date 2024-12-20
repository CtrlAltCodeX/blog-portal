<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Listing;
use App\Models\UserListingInfo;

class ImportListingCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(protected $data, protected $user, protected $postID, protected $image) {}

    /**
     * Execute the job.
     */
    public function handle()
    {
        $data = [
            'title' => $this->data->title,
            'description' => $this->data->description,
            'mrp' => $this->data->mrp,
            'selling_price' => $this->data->selling_price,
            'publisher' => $this->data->publisher,
            'author_name' => $this->data->author_name,
            'edition' => $this->data->edition,
            'categories' => $this->data->categories,
            'sku' => $this->data->sku ?? null,
            'language' => $this->data->language,
            'no_of_pages' => $this->data->no_of_pages,
            'condition' => $this->data->condition ?? null,
            'binding_type' => $this->data->binding ?? null,
            'insta_mojo_url' => $this->data->url ?? null,
            'images' => json_encode([$this->image]),
            'multiple_images' => $this->data->multiple_images ?? null,
            'status' => 0,
            'created_by' => $this->user,
            'is_bulk_upload' => 1,
            'product_id' => $this->postID ? $this->postID : ''
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
}
