<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct(protected $otp, $name)
    {
        $this->name = $name;
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
        return $this->view('emails.otp')->with('otp', $this->otp);
    }
}
