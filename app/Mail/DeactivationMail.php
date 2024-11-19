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

    /**
     * Create a new message instance.
     */
    public function __construct($msg, $count)
    {
        $this->msg = $msg;
        $this->count = $count;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Urgent Performance Warning: Low Weekly Product Listings',
        );
    }

    public function build()
    {
        return $this->view('emails.deactivation-mail')
            ->with('msg', $this->msg)
            ->with('count', $this->count);
    }
}
