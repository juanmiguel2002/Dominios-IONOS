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
    public $diasRestantes;

    public function __construct($dominio, $fecha, $diasRestantes)
    {
        $this->dominio = $dominio;
        $this->fecha = $fecha;
        $this->diasRestantes = $diasRestantes;
    }
    public function build()
    {
        return $this->subject('Correo desde Livewire')
                    ->view('emails.renovacion');
    }
}
