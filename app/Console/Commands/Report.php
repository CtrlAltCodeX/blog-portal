<?php

namespace App\Console\Commands;

use App\Mail\Report as MailReport;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Report to Admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $user = User::find(1);
        
        // Mail::to('akathuria289@gmail.com')->send(new WelcomeMail($user));
        
        Mail::to('abhishek86478@gmail.com')->send(new MailReport());
    }
}
