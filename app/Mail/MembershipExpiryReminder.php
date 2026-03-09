<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class MembershipExpiryReminder extends Mailable
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Your Vault Membership is Expiring Soon')
                    ->view('emails.membership-reminder');
    }
}