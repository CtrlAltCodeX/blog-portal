<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userDetails;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->userDetails = $data;
    }

    /**
     * Email view
     *
     * @return void
     */
    public function build()
    {
        return $this->view('emails.details')
            ->subject('Login Details'); // Blade view file for your email content
    }
}
