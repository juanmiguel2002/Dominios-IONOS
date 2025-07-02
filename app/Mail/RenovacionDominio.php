<?php

namespace App\Mail;

use Carbon\Carbon;
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
        $this->fecha = Carbon::parse($fecha)->format('d/m/Y');
    }

    public function build()
    {
        return $this->subject("Aviso de renovación de dominio: {$this->dominio}")
                    ->view('emails.renovacion');
    }
}
