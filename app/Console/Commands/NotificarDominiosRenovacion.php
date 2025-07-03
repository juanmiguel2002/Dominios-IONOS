<?php

namespace App\Console\Commands;

use App\Mail\DominiosPorRenovar;
use App\Services\IonosService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotificarDominiosRenovacion extends Command
{
    protected $signature = 'ionos:notificar-renovacion';
    protected $description = 'Notifica al administrador sobre dominios a renovar en los próximos 30 días';

    public function handle(IonosService $ionos)
    {
        try {
            $dominios = $ionos->obtenerDominios(); // Ajusta el límite según necesidad

            $hoy = Carbon::now();
            $limite = $hoy->copy()->addDays(30);

            $aNotificar = array_filter($dominios[], function ($dominio) use ($limite) {
                $fechaRenovacion = $dominio['status']['provisioningStatus']['setToRenewOn'] ?? null;
                return $fechaRenovacion && Carbon::parse($fechaRenovacion)->between(now(), $limite);
            });

            if (!empty($aNotificar)) {
                Mail::to('web@ivarscomagenciadepublicidad.com')
                    ->send(new DominiosPorRenovar(array_values($aNotificar)));

                $this->info('Correo enviado con ' . count($aNotificar) . ' dominios próximos a renovar.');
            } else {
                $this->info('No hay dominios por renovar en los próximos 30 días.');
            }

        } catch (\Exception $e) {
            $this->error('Error al consultar los dominios: ' . $e->getMessage());
        }
    }
}
