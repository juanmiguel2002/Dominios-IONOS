<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IonosService;
use Illuminate\Support\Facades\Mail;
use App\Mail\RenovacionDominio;
use Carbon\Carbon;

class EnviarRenovaciones extends Command
{
    protected $signature = 'dominios:enviar-renovaciones';
    protected $description = 'Enviar email de renovación 30 días antes de la expiración del dominio';

    public function handle(IonosService $ionos)
    {
        $this->info('Ejecutando comando...');
        try {
            $dominios = $ionos->obtenerDominios();

            foreach ($dominios as $dominio) {
                $fechaRenovacion = $dominio['provisioningStatus']['setToRenewOn'] ?? null;

                if (!$fechaRenovacion) continue;

                $fecha = Carbon::parse($fechaRenovacion);
                if ($fecha->isSameDay(Carbon::now()->addDays(15))) {
                    $nombreDominio = $dominio['name'];

                    // Envía el correo
                    Mail::to('web@ivarscomagenciadepublicidad.com')
                        ->send(new RenovacionDominio($nombreDominio, $fecha));

                    $this->info("Correo enviado para el dominio: {$nombreDominio}");
                }
            }
        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
