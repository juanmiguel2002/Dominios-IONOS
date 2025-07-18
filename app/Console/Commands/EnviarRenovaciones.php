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
        $this->info('Ejecutando revisión de dominios...');

        try {
            $dominios = $ionos->obtenerDominios();

            foreach ($dominios as $dominio) {
                $fechaRenovacion = $dominio['provisioningStatus']['setToRenewOn'] ?? null;

                if (!$fechaRenovacion) {
                    $this->warn("Dominio {$dominio['name']} no tiene fecha de renovación.");
                    continue;
                }

                $fecha = Carbon::parse($fechaRenovacion);
                $diasRestantes = now()->diffInDays($fecha, false); // negativo si ya pasó

                if (in_array($diasRestantes, [30, 15, 5])) {
                    $nombreDominio = $dominio['name'];

                    Mail::to('web@ivarscomagenciadepublicidad.com')
                        ->send(new RenovacionDominio($nombreDominio, $fecha, $diasRestantes));

                    $this->info("Correo enviado para el dominio: {$nombreDominio} (quedan {$diasRestantes} días)");
                }
            }

        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
