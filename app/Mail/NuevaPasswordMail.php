<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombreCompleto;
    public string $nuevaPassword;
    public string $tipoUsuario;

    public function __construct(string $nombreCompleto, string $nuevaPassword, string $tipoUsuario = 'estudiante')
    {
        $this->nombreCompleto = $nombreCompleto;
        $this->nuevaPassword = $nuevaPassword;
        $this->tipoUsuario = $tipoUsuario;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Contraseña Temporal - Sistema ISTPET',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nueva-password',
        );
    }
}
