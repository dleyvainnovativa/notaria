<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registerLink;
    public $memorialLink;
    public $memorial;

    public function __construct($registerLink, $memorialLink, $memorial)
    {
        $this->registerLink = $registerLink;
        $this->memorialLink = $memorialLink;
        $this->memorial = $memorial;
    }

    public function build()
    {
        return $this->subject('Has sido invitado a un memorial')
            ->view('mail.invitation');
    }
}
