<?php

namespace App\Mail;

use App\Models\Resident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Resident $resident) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to Community Service Desk');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.welcome');
    }
}