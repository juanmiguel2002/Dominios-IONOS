<?php

use App\Livewire\DominiosPlesk;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

it('renderiza sin romper si el servidor Plesk no responde', function () {
    Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('timeout'));

    Livewire::test(DominiosPlesk::class)
        ->assertOk()
        ->assertSee('Offline');
});

it('renderiza sin romper si el servidor no está configurado', function () {
    Http::fake(['*' => Http::response([], 200)]);

    Livewire::test(DominiosPlesk::class)
        ->set('server', 'no-existe')
        ->assertOk()
        ->assertSet('error', 'No se pudo conectar al servidor seleccionado.');
});
