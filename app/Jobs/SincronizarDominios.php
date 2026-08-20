<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Services\IonosService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SincronizarDominios implements ShouldQueue
{
    use Queueable;

    /**
     * Evita que dos sincronizaciones se solapen.
     */
    public int $timeout = 300;

    /**
     * Sincroniza el catálogo de dominios de IONOS con la tabla local.
     *
     * @return array{creados:int,actualizados:int,inactivos:int}
     */
    public function handle(IonosService $ionos): array
    {
        $dominios = $ionos->obtenerDominios(fresh: true);

        $creados = 0;
        $actualizados = 0;
        $vistos = [];

        foreach ($dominios as $d) {
            $ionosId = $d['id'] ?? null;
            $nombre = $d['name'] ?? null;

            if (! $ionosId || ! $nombre) {
                continue;
            }

            $vistos[] = $ionosId;

            $domain = Domain::updateOrCreate(
                ['ionos_id' => $ionosId],
                [
                    'name' => $nombre,
                    'provisioning_status' => $d['provisioningStatus']['status'] ?? null,
                    'set_to_renew_on' => $d['provisioningStatus']['setToRenewOn'] ?? null,
                    'set_to_expire_on' => $d['provisioningStatus']['setToExpireOn'] ?? null,
                    'pending_provisioning' => (bool) ($d['provisioningStatus']['pendingProvisioning'] ?? false),
                    'raw' => $d,
                    'is_active' => true,
                    'synced_at' => now(),
                ]
            );

            $domain->wasRecentlyCreated ? $creados++ : $actualizados++;
        }

        // Los dominios que ya no aparecen en IONOS se marcan inactivos (no se borran
        // para conservar notas y datos propios). Si la respuesta vino vacía no se
        // desactiva nada, para no arrasar la tabla ante una respuesta anómala.
        $inactivos = 0;
        if ($vistos !== []) {
            $inactivos = Domain::where('is_active', true)
                ->whereNotIn('ionos_id', $vistos)
                ->update(['is_active' => false]);
        }

        Log::info('Sincronización de dominios IONOS', compact('creados', 'actualizados', 'inactivos'));

        return compact('creados', 'actualizados', 'inactivos');
    }
}
