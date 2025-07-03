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
        ])->acceptJson()->get('https://api.hosting.ionos.com/domains/v1/domainitems/domains?includeProvisioningStatus=true');

        $data = $response->json();
        if ($response->failed()) {
            throw new \Exception('Error al obtener los dominios: ' . $data['message'] ?? 'Error desconocido');
        }

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

        return $data;
    }

    public function obtenerContactoDominio($id)
    {
        $response = Http::withHeaders([
            'X-Api-Key' => config('services.ionos.key'),
        ])->acceptJson()->get("https://api.hosting.ionos.com/domains/v1/domainitems/{$id}/contacts");

        $data = $response->json();

        if ($response->failed()) {
            throw new \Exception('Error al obtener el dominio: ' . ($data['message'] ?? 'Error desconocido'));
        }
        //dd($data['registrant']['postalInfo']['address']['postalCode']);
        // Puedes ajustar el tipo de contacto que quieres (adminContact, ownerContact, etc.)
        return $data['registrant'] ?? [];
    }

}
