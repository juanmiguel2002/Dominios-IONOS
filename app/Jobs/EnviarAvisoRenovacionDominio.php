<?php

namespace App\Jobs;

use App\Mail\RenovacionDominio;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarAvisoRenovacionDominio implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $nombreDominio;
    protected Carbon $fecha;
    protected int $diasRestantes;

    /**
     * Crear nueva instancia del Job.
     */
    public function __construct(string $nombreDominio, Carbon $fecha, int $diasRestantes)
    {
        $this->nombreDominio = $nombreDominio;
        $this->fecha = $fecha;
        $this->diasRestantes = $diasRestantes;
    }

    /**
     * Ejecutar el Job.
     */
    public function handle(): void
    {
        Mail::to('info@ivarscom.com')
            ->cc('web@ivarscomagenciadepublicidad.com')
            ->send(new RenovacionDominio(
                $this->nombreDominio,
                $this->fecha,
                $this->diasRestantes
            ));
    }
}
