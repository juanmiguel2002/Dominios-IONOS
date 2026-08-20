<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IonosService
{
    private const BASE_URL = 'https://api.hosting.ionos.com/domains/v1';

    /** Segundos que se cachea el listado de dominios. */
    private const CACHE_TTL = 300;

    /**
     * Cliente HTTP preconfigurado con la API key, timeout y reintentos.
     */
    protected function client(): PendingRequest
    {
        return Http::withHeaders([
            'X-Api-Key' => config('services.ionos.key'),
        ])
            ->acceptJson()
            ->timeout(10)
            ->retry(2, 200, throw: false);
    }

    public function obtenerDominios(bool $pendingProvisioning = false): Collection
    {
        $cacheKey = 'ionos.dominios.' . ($pendingProvisioning ? 'pending' : 'all');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($pendingProvisioning) {
            $response = $this->client()->get(self::BASE_URL . '/domainitems/domains', [
                'includeProvisioningStatus' => 'true',
                'pendingProvisioning' => $pendingProvisioning ? 'true' : 'false',
            ]);

            $data = $response->json();

            if ($response->failed()) {
                throw new \Exception('Error al obtener los dominios: ' . ($data['message'] ?? 'Error desconocido'));
            }

            return collect($data['domains'] ?? []);
        });
    }

    public function obtenerDetallesDominio(string $domainId): array
    {
        $response = $this->client()->get(self::BASE_URL . "/domainitems/{$domainId}");

        $data = $response->json();

        if ($response->failed()) {
            throw new \Exception('Error al obtener los detalles del dominio: ' . ($data['message'] ?? 'Error desconocido'));
        }

        return $data;
    }

    public function obtenerContactoDominio(?string $id): array
    {
        if (empty($id)) {
            return [];
        }

        $response = $this->client()->get(self::BASE_URL . "/domainitems/{$id}/contacts");

        $data = $response->json();

        if ($response->failed()) {
            throw new \Exception('Error al obtener el dominio: ' . ($data['message'] ?? 'Error desconocido'));
        }

        // Puedes ajustar el tipo de contacto que quieres (adminContact, ownerContact, etc.)
        return $data['registrant'] ?? [];
    }

    /**
     * Invalida la caché del listado de dominios (usar tras altas/bajas/cambios).
     */
    public function olvidarCacheDominios(): void
    {
        Cache::forget('ionos.dominios.all');
        Cache::forget('ionos.dominios.pending');
    }
}
