<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PleskService
{

    protected $baseUrl;
    protected $username;
    protected $password;
    protected $verifySsl;

    public function __construct()
    {
        $this->baseUrl = config('services.plesk.host');
        $this->username = config('services.plesk.username');
        $this->password = config('services.plesk.password');
        $this->verifySsl = config('services.plesk.verify_ssl', true);
    }

    protected function client()
    {
        return Http::withBasicAuth($this->username, $this->password)
            ->withOptions(['verify' => $this->verifySsl])
            ->acceptJson();
    }

    public function obtenerHosting()
    {
        $response = $this->client()->get($this->baseUrl . '/api/v2/domains');

        if (!$response->successful()) {
            return collect([]);
        }

        $dominios = $response->json();

        //dd(collect($dominios));

        return collect($dominios);
    }

    public function estado($id)
    {
        $response = $this->client()->get($this->baseUrl . "/api/v2/domains/{$id}/status");
        $estado = $response->json();

        return $estado;
    }
}
