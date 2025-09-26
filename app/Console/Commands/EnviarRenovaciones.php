<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IonosService;
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
                $fechaRenovacion = $dominio['provisioningStatus']['setToExpireOn']
                    ?? $dominio['provisioningStatus']['setToRenewOn']
                    ?? null;

                if (!$fechaRenovacion) {
                    $this->warn("Dominio {$dominio['name']} no tiene fecha de renovación.");
                    continue;
                }

                $fecha = Carbon::parse($fechaRenovacion);
                $diasRestantes = now()->diffInDays($fecha, false);

                if ($diasRestantes < 0) {
                    $this->warn("Dominio {$dominio['name']} ya expiró.");
                    continue;
                }

                if (in_array($diasRestantes, [30, 15, 5])) {
                    $nombreDominio = $dominio['name'];

                    RenovacionDominio::dispatch(
                        $nombreDominio,
                        $fecha,
                        $diasRestantes
                    );

                    $this->info("Job de correo encolado para dominio: {$nombreDominio} (quedan {$diasRestantes} días)");
                }
            }

        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }

}
