<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Listing;
use App\Models\UserListingCount;
use App\Models\UserListingInfo;
use Carbon\Carbon;

class ImportListingCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data = [];

    protected $user = 0;


    /**
     * Create a new job instance.
     */
    public function __construct($data, $userId)
    {
        $this->data = $data;
        $this->user = $userId;
    }

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
            'images' => $this->data->images,
            'multiple_images' => $this->data->multiple_images ?? null,
            'status' => 0,
            'created_by' => $this->user,
            'is_bulk_upload' => 1
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

        // $this->updateTheCount('Created', 'create_count');

        if ($listing) {
            return true;
        } else {
            return false;
        }
    }

    public function updateTheCount($status, $column)
    {
        // Get the current date
        $currentDate = Carbon::now()->toDateString(); // This will give you 'YYYY-MM-DD' format

        // Check if a record exists for the current date and user
        $userListingCount = UserListingCount::where('user_id', auth()->user()->id)
            ->where('status', $status)
            ->whereDate('created_at', $currentDate)
            ->first();

        if ($userListingCount) {
            // If record exists, increment the approved_count
            $userListingCount->increment($column);
            $userListingCount->status = $status; // Update status if needed
            $userListingCount->save();
        } else {
            // If no record exists, create a new record
            UserListingCount::create([
                'user_id' => $this->user,
                $column => 1,
                'status' => $status,
            ]);
        }
    }
}
