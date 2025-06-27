<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class IonosService
{
    public function obtenerDominios() : Collection
    {
        $response = Http::withHeaders([
            'X-Api-Key' => config('services.ionos.key'),
        ])->acceptJson()->get('https://api.hosting.ionos.com/domains/v1/domainitems?includeDomainStatus=true');

        $data = $response->json();
        if ($response->failed()) {
            throw new \Exception('Error al obtener los dominios: ' . $data['message'] ?? 'Error desconocido');
        }
        //dd($data['domains'][0]['status']['provisioningStatus']['setToRenewOn']);
        return collect($data['domains']);
    }

    public function obtenerDetallesDominio(string $domainId) : array
    {
        $response = Http::withHeaders([
            'X-Api-Key' => config('services.ionos.key'),
        ])->acceptJson()->get("https://api.hosting.ionos.com/domains/v1/domainitems/{$domainId}");

        $data = $response->json();
        if ($response->failed()) {
            throw new \Exception('Error al obtener los detalles del dominio: ' . $data['message'] ?? 'Error desconocido');
        }

        //dd($data);


        return $data;
    }
}
