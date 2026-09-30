<?php

use App\Jobs\SincronizarDominios;
use App\Models\Domain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function fakeIonosDomains(array $domains): void
{
    Http::fake([
        'api.hosting.ionos.com/*' => Http::response(['domains' => $domains], 200),
    ]);
}

it('crea dominios nuevos a partir de la respuesta de IONOS', function () {
    fakeIonosDomains([
        [
            'id' => 'abc-123',
            'name' => 'ejemplo.com',
            'tld' => 'com',
            'provisioningStatus' => [
                'status' => 'ACTIVE',
                'type' => 'REGISTRATION_IN_PROGRESS',
                'setToRenewOn' => '2027-01-15',
                'createdDate' => '2026-01-15',
                'pendingProvisioning' => false,
            ],
        ],
    ]);

    $resultado = app()->call([new SincronizarDominios, 'handle']);

    expect($resultado['creados'])->toBe(1);
    expect(Domain::where('ionos_id', 'abc-123')->exists())->toBeTrue();

    $domain = Domain::first();
    expect($domain->name)->toBe('ejemplo.com');
    expect($domain->tld)->toBe('com');
    expect($domain->provisioning_status)->toBe('ACTIVE');
    expect($domain->provisioning_type)->toBe('REGISTRATION_IN_PROGRESS');
    expect($domain->is_active)->toBeTrue();
    expect($domain->set_to_renew_on->toDateString())->toBe('2027-01-15');
    expect($domain->created_date->toDateString())->toBe('2026-01-15');
});

it('actualiza un dominio existente sin duplicarlo ni perder los datos propios', function () {
    $existente = Domain::create([
        'ionos_id' => 'abc-123',
        'name' => 'ejemplo.com',
        'client_name' => 'Cliente Uno',
        'notes' => 'Nota importante',
        'is_active' => true,
    ]);

    fakeIonosDomains([
        [
            'id' => 'abc-123',
            'name' => 'ejemplo.com',
            'provisioningStatus' => ['status' => 'RENEWING'],
        ],
    ]);

    $resultado = app()->call([new SincronizarDominios, 'handle']);

    expect($resultado['actualizados'])->toBe(1);
    expect($resultado['creados'])->toBe(0);
    expect(Domain::count())->toBe(1);

    $existente->refresh();
    expect($existente->provisioning_status)->toBe('RENEWING');
    // Los datos propios se conservan
    expect($existente->client_name)->toBe('Cliente Uno');
    expect($existente->notes)->toBe('Nota importante');
});

it('marca inactivos los dominios que ya no están en IONOS sin borrarlos', function () {
    Domain::create(['ionos_id' => 'viejo-1', 'name' => 'caducado.com', 'is_active' => true]);

    fakeIonosDomains([
        ['id' => 'nuevo-1', 'name' => 'vigente.com', 'provisioningStatus' => ['status' => 'ACTIVE']],
    ]);

    $resultado = app()->call([new SincronizarDominios, 'handle']);

    expect($resultado['inactivos'])->toBe(1);
    expect(Domain::where('ionos_id', 'viejo-1')->first()->is_active)->toBeFalse();
    expect(Domain::where('ionos_id', 'nuevo-1')->first()->is_active)->toBeTrue();
    // No se borra ninguno
    expect(Domain::count())->toBe(2);
});

it('no desactiva nada si IONOS devuelve una lista vacía', function () {
    Domain::create(['ionos_id' => 'x-1', 'name' => 'uno.com', 'is_active' => true]);

    fakeIonosDomains([]);

    $resultado = app()->call([new SincronizarDominios, 'handle']);

    expect($resultado['inactivos'])->toBe(0);
    expect(Domain::where('ionos_id', 'x-1')->first()->is_active)->toBeTrue();
});
