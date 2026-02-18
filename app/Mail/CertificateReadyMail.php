<?php

namespace App\Mail;

use App\Models\CertificateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CertificateRequest $request) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Certificate Ready for Pickup');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.certificate_ready');
    }
}