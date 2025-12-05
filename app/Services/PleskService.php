<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class PleskService
{
    protected $serverKey;
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $verifySsl;
    protected $serverName;

    public function __construct($serverKey = 'server1')
    {
        $this->serverKey = $serverKey;

        $config = config("services.plesk.servers.{$serverKey}");

        if (!$config) {
            throw new Exception("El servidor Plesk '{$serverKey}' no está configurado.");
        }

        $this->serverName = $config['name'];
        $this->baseUrl = rtrim($config['host'], '/');
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->verifySsl = $config['verify_ssl'] ?? true;
    }

    protected function client()
    {
        return Http::withBasicAuth($this->username, $this->password)
            ->withOptions(['verify' => $this->verifySsl])
            ->acceptJson();
    }

    /** 🌐 Comprobar estado del servidor */
    public function estadoServidor()
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/api/v2/server");

            return $response->successful()
                ? 'online'
                : 'offline';

        } catch (\Throwable $e) {
            return 'offline';
        }
    }

    /** 📄 Obtener lista de dominios */
    public function obtenerHosting()
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/api/v2/domains");
        } catch (\Throwable $e) {
            return collect([]);
        }

        return $response->successful()
            ? collect($response->json())
            : collect([]);
    }

    /** 🔎 Obtener estado de un dominio */
    public function estado($id)
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/api/v2/domains/{$id}/status");
        } catch (\Throwable $e) {
            return ['status' => 'unknown'];
        }

        return $response->json() ?? ['status' => 'unknown'];
    }
}
