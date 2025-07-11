<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ManualBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $timeout = 1200; // 20 minutes

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Call the artisan command for database backup
            Artisan::call('backup:listing');

            \Log::info('Database backup completed successfully.');
        } catch (\Exception $e) {
            \Log::error('Database backup failed: ' . $e->getMessage());
            // Optionally rethrow the exception to mark the job as failed
            throw $e;
        }
    }
}
