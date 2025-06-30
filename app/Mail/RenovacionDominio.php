<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenovacionDominio extends Mailable
{
    use Queueable, SerializesModels;

    public $dominio;
    public $fecha;

    public function __construct($dominio, $fecha = null)
    {
        $this->dominio = $dominio;
        $this->fecha = $fecha ?? now()->addDays(30)->format('d-m-Y');
    }

    public function build()
    {
        return $this->subject("Aviso de renovación de dominio: {$this->dominio}")
                    ->view('emails.renovacion-dominio');
    }
}
