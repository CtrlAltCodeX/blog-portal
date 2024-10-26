<?php

namespace App\Console\Commands;

use App\Mail\Report as MailReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Mail::to('akathuria289@gmail.com')->send(new MailReport());
    }
}
