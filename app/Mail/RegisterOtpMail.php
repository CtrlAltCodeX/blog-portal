<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public $otp;
    public $userData;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $userData)
    {
        $this->otp = $otp;
        $this->userData = $userData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OTP For Register Listing Portal',
        );
    }

    /**
     * OTP
     *
     * @return void
     */
    public function build()
    {
        return $this->subject('Your Exam360 Registration OTP')
            ->view('emails.otp')
            ->with([
                'otp' => $this->otp,
                'user' => $this->userData
            ]);
    }
}
