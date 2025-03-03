<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $status;
    public $subject;
    public $body;

    public function __construct($status, $subject, $body)
    {
        $this->status = $status;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.status_notification')
                    ->with([
                        'status' => $this->status,
                        'subject' => $this->subject,
                        'body' => $this->body
                    ]);
    }
}



