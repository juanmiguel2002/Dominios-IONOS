<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PleskService
{

    protected $baseUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('services.plesk.host');
        $this->username = config('services.plesk.username');
        $this->password = config('services.plesk.password');
    }

    public function obtenerHosting()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'text/xml',
        ])
        ->withBasicAuth($this->username, $this->password)
        ->acceptJson()->get($this->baseUrl);

        $hosting = $response->json();

        return collect($hosting);
    }

    public function estado($id) {
        $response = Http::withHeaders([
            'Content-Type' => 'text/xml',
        ])
        ->withBasicAuth($this->username, $this->password)
        ->acceptJson()->get("https://ivarscomagenciadepublicidad.com:8443/api/v2/domains/{$id}/status");

        $estado = $response->json();

        return $estado;
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

        // Puedes ajustar el tipo de contacto que quieres (adminContact, ownerContact, etc.)
        return $data['registrant'] ?? [];
    }

}
