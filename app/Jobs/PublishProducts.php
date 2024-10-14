<?php

namespace App\Jobs;

use App\Events\PublishProducts as EventsPublishProducts;
use App\Models\Listing;
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

        if (isset($getData->images)) {
            $getData['images'] = [$getData->images];
        }

        $getData['label'] = $getData->categories;
        $getData['pages'] = $getData->no_of_pages;
        $getData['binding'] = $getData->binding_type;
        $getData['url'] = $getData->insta_mojo_url;
        $getData['publication'] = $getData->publisher;
        $result = app('App\Services\GoogleService')
            ->createPost($getData->toArray(), $this->isDraft, $getData->created_by);

        if ($result?->error?->code == 401) {
            event(new EventsPublishProducts($this->id, $result->error->message));
        }
        //  else if (isset($result->id)) {
            
        // }
    }
}
