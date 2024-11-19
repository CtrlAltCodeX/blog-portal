<?php

namespace App\Console\Commands;

use App\Mail\DeactivationMail;
use App\Models\User;
use App\Models\UserListingCount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AccountDeactivationMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:account-deactivation-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command help you to generate an auto mail';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startOfWeek = \Carbon\Carbon::now()->subDays(7);
        $endOfWeek = \Carbon\Carbon::yesterday();

        $users = User::where('status', 1)
            ->where('show_health', 1)
            ->get();

        foreach ($users as $user) {
            $currentWeekDataCreated = UserListingCount::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->where('user_id', $user->id)
                ->sum('create_count');

            if ($currentWeekDataCreated <= 120) {
                Mail::to($user->email)->send(new DeactivationMail('DEACTIVATION', $currentWeekDataCreated));

                Mail::to('abhishek86478@gmail.com')->send(new DeactivationMail('DEACTIVATION', $currentWeekDataCreated));
            } elseif ($currentWeekDataCreated >= 121 && $currentWeekDataCreated <= 149) {
                Mail::to($user->email)->send(new DeactivationMail('AT RISK', $currentWeekDataCreated));

                Mail::to('abhishek86478@gmail.com')->send(new DeactivationMail('DEACTIVATION', $currentWeekDataCreated));
            }
        }
    }
}
