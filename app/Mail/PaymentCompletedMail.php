<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentCompletedMail extends Mailable
{
    public $user;
    public $memorial;
    public $payment;
    public $qrCode;

    public function __construct($user, $memorial, $payment, $qrCode)
    {
        $this->user = $user;
        $this->memorial = $memorial;
        $this->payment = $payment;
        $this->qrCode = $qrCode;
    }

    public function build()
    {
        return $this->subject('Pago confirmado')
            ->view('mail.payment', [$this->user, $this->memorial, $this->payment, $this->qrCode]);
    }
}
