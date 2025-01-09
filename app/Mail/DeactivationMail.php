<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeactivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $msg;
    public $count;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($msg, $count, $user)
    {
        $this->msg = $msg;
        $this->count = $count;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Urgent Actions Required: Performance Issues Flagged | Freelance Partner',
        );
    }

    public function build()
    {
        return $this->view('emails.deactivation-mail')
            ->with('msg', $this->msg)
            ->with('count', $this->count)
            ->with('user', $this->user);
    }
}
