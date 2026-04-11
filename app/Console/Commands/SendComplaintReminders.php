<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Complaint;
use App\Models\User;
use App\Mail\ComplaintReminderMail;
use Illuminate\Support\Facades\Mail;

class SendComplaintReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'complaints:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily reminder emails for open complaints to Admin and Department Heads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $openStatuses = ['pending', 'verification'];

        $complaints = Complaint::whereIn('status', $openStatuses)->with(['department'])->get();

        if ($complaints->isEmpty()) {
            $this->info('No open complaints found for reminders.');
            return;
        }

        $adminEmails = User::role('Super Admin')->pluck('email')->toArray();

        foreach ($complaints as $complaint) {
            $deptHeadEmail = $complaint->department->email;

            if ($adminEmails || $deptHeadEmail) {
                Mail::to($adminEmails)
                    ->cc($deptHeadEmail)
                    ->send(new ComplaintReminderMail($complaint));

                $this->info('Reminder sent for Ticket: ' . $complaint->complaint_id);
            }
        }

        $this->info('Total reminders sent: ' . $complaints->count());
    }
}