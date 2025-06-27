<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DominiosPorRenovar extends Mailable
{
    use Queueable, SerializesModels;

    public $dominio;
    public $fecha;

    public function __construct( array $notificacion)
    {
        $this->dominio = $notificacion['dominio'];
        $this->fecha = $notificacion['fecha'] ?? now()->addDays(30)->format('d-m-Y');
    }

    public function build()
    {
        return $this->subject("Aviso de renovación de dominio: {$this->dominio}")
                    ->view('emails.renovacion-dominio');
    }
}
