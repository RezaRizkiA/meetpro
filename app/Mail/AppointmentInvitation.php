<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    // 1. Terima data appointment dari Controller
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    // 2. Set Subjek Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Confirmation: ' . $this->appointment->topic,
        );
    }

    // 3. Arahkan ke file View (Blade)
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment.invitation', // Kita akan buat file ini setelah ini
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
