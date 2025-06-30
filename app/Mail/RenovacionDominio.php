<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenovacionDominio extends Mailable
{
    use Queueable, SerializesModels;

    public $dominio;
    public $fecha;

    public function __construct($dominio, $fecha)
    {
        $this->dominio = $dominio;
        $this->fecha = $fecha;
    }

    public function build()
    {
        return $this->subject("Aviso de renovación de dominio: {$this->dominio}")
                    ->view('emails.renovacion-dominio');
    }
}
