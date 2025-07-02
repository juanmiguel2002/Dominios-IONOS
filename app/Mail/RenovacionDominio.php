<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenovacionDominio extends Mailable
{
    use Queueable, SerializesModels;

    public $dominio;
    public $fecha;

    public function __construct($dominio, $fecha)
    {
        $this->dominio = $dominio;
        $this->fecha = Carbon::parse($fecha)->format('d/m/Y');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aviso de renovación de dominio: '. $this->dominio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renovacion',
            with: [
                'dominio' => $this->dominio,
                'fecha' => $this->fecha,
            ]

        );
    }
}
