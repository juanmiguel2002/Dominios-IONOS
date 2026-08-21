<?php

use App\Services\IonosService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('obtiene la colección de dominios de IONOS', function () {
    Http::fake([
        'api.hosting.ionos.com/*' => Http::response([
            'domains' => [
                ['id' => 'a', 'name' => 'uno.com'],
                ['id' => 'b', 'name' => 'dos.com'],
            ],
        ], 200),
    ]);

    $dominios = app(IonosService::class)->obtenerDominios();

    expect($dominios)->toHaveCount(2);
    expect($dominios->first()['name'])->toBe('uno.com');
});

it('lanza una excepción con el mensaje de error cuando IONOS falla', function () {
    Http::fake([
        'api.hosting.ionos.com/*' => Http::response(['message' => 'Clave inválida'], 401),
    ]);

    app(IonosService::class)->obtenerDominios();
})->throws(Exception::class, 'Clave inválida');

it('cachea el listado y no repite la petición HTTP', function () {
    Http::fake([
        'api.hosting.ionos.com/*' => Http::response(['domains' => [['id' => 'a', 'name' => 'uno.com']]], 200),
    ]);

    $service = app(IonosService::class);
    $service->obtenerDominios();
    $service->obtenerDominios(); // debería salir de caché

    Http::assertSentCount(1);
});

it('con fresh=true invalida la caché y vuelve a pedir', function () {
    Http::fake([
        'api.hosting.ionos.com/*' => Http::response(['domains' => [['id' => 'a', 'name' => 'uno.com']]], 200),
    ]);

    $service = app(IonosService::class);
    $service->obtenerDominios();
    $service->obtenerDominios(fresh: true);

    Http::assertSentCount(2);
});
