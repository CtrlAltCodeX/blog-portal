<?php

namespace App\Jobs;

use App\Events\PublishProducts as EventsPublishProducts;
use App\Models\Listing;
use App\Models\UserListingCount;
use App\Models\UserListingInfo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id = [];

    protected $isDraft = 0;
    
    protected $user = 0;

    /**
     * Create a new job instance.
     */
    public function __construct($id, $isDraft, $userId)
    {
        $this->id = $id;

        $this->isDraft = $isDraft;

        $this->user = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $getData = Listing::find($this->id);

        if(isset($getData->images)){
            $getData['images'] = json_decode($getData->images);
        }
        $getData['label'] = $getData->categories;
        $getData['pages'] = $getData->no_of_pages;
        $getData['binding'] = $getData->binding_type;
        $getData['url'] = $getData->insta_mojo_url;
        $getData['publication'] = $getData->publisher;
        $result = app('App\Services\GoogleService')
            ->createPost($getData->toArray(), $this->isDraft, $getData->created_by, $this->user);
            
        if ($result?->error?->code == 401) {
            event(new EventsPublishProducts($this->id, $result->error->message));
        }
        // else if (isset($result->id)) {
        //     $listing = Listing::find($this->id);
            
        //     $this->updateTheCount('Created', $this->user);

        //     $additionalInfo = UserListingInfo::where('listings_id', $listing->id)
        //         ->first();
                
        //         \Log::info($additionalInfo);

        //     // if ($additionalInfo) {
        //         $additionalInfo->update([
        //             'status' => 1,
        //             'approved_by' => $this->user,
        //             'approved_at' => now()
        //         ]);
        //     // }
            
        //     $listing->delete();
        // }
    }
    
    public function updateTheCount($status, $user)
    {
        // Get the current date
        $currentDate = Carbon::now()->toDateString(); // This will give you 'YYYY-MM-DD' format

        // Check if a record exists for the current date and user
        $userListingCount = UserListingCount::where('user_id', $user)
            ->where('status', $status)
            ->whereDate('created_at', $currentDate)
            ->first();

        if ($userListingCount) {
            // If record exists, increment the approved_count
            $userListingCount->increment('create_count');
            $userListingCount->status = $status; // Update status if needed
            $userListingCount->save();
        } else {
            // If no record exists, create a new record
            UserListingCount::create([
                'user_id' => $user,
                'approved_count' => 1,
                'status' => $status,
            ]);
        }
    }
}