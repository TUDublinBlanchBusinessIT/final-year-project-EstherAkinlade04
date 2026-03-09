<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\MembershipExpiryReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendExpiryReminders extends Command
{
    protected $signature = 'memberships:reminders';
    protected $description = 'Send membership expiry reminder emails';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(3)->startOfDay();

        $users = User::whereDate('end_date', $targetDate)->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(
                new MembershipExpiryReminder($user)
            );
        }

        $this->info('Expiry reminders sent successfully.');
    }
}