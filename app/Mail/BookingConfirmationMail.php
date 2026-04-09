<?php

namespace App\Mail;

use App\Models\FitnessClass;
use Illuminate\Mail\Mailable;

class BookingConfirmationMail extends Mailable
{
    public $class;

    public function __construct(FitnessClass $class)
    {
        $this->class = $class;
    }

    public function build()
    {
        return $this->subject('Class Booking Confirmed 💪')
            ->view('emails.booking');
    }
}