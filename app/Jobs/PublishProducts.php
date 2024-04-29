<?php

namespace App\Jobs;

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

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $getData = Listing::find($this->id);

        $getData['images'] = [$getData->images];
        $getData['label'] = $getData->categories;
        $getData['pages'] = $getData->no_of_pages;
        $getData['binding'] = $getData->binding_type;
        $getData['url'] = $getData->insta_mojo_url;
        $getData['publication'] = $getData->publisher;

        app('App\Services\GoogleService')->createPost($getData->toArray());
    }
}
