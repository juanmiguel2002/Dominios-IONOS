<?php

use App\Livewire\ConsultarDominios;
use App\Livewire\ResumenDominios;
use App\Models\Domain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('lista los dominios activos desde la base de datos', function () {
    Domain::create(['ionos_id' => 'a', 'name' => 'activo.com', 'tld' => 'com', 'is_active' => true]);
    Domain::create(['ionos_id' => 'b', 'name' => 'inactivo.com', 'tld' => 'com', 'is_active' => false]);

    Livewire::test(ConsultarDominios::class)
        ->assertSee('activo.com')
        ->assertDontSee('inactivo.com');
});

it('filtra por búsqueda usando la consulta SQL', function () {
    Domain::create(['ionos_id' => 'a', 'name' => 'tienda.com', 'tld' => 'com', 'is_active' => true]);
    Domain::create(['ionos_id' => 'b', 'name' => 'blog.es', 'tld' => 'es', 'is_active' => true]);

    Livewire::test(ConsultarDominios::class)
        ->set('search', 'tienda')
        ->assertSee('tienda.com')
        ->assertDontSee('blog.es');
});

it('el toggle muestra solo los dominios en transferencia', function () {
    Domain::create(['ionos_id' => 'a', 'name' => 'normal.com', 'is_active' => true, 'provisioning_type' => null]);
    Domain::create(['ionos_id' => 'b', 'name' => 'transfiriendo.com', 'is_active' => true, 'provisioning_type' => 'REGISTRATION_IN_PROGRESS']);

    Livewire::test(ConsultarDominios::class)
        ->call('estadoDominio')
        ->assertSee('transfiriendo.com')
        ->assertDontSee('normal.com');
});

it('el resumen calcula los KPIs desde la tabla', function () {
    Domain::create(['ionos_id' => 'a', 'name' => 'uno.com', 'is_active' => true, 'set_to_expire_on' => now()->addDays(10)]);
    Domain::create(['ionos_id' => 'b', 'name' => 'dos.com', 'is_active' => true, 'set_to_expire_on' => now()->addDays(200)]);
    Domain::create(['ionos_id' => 'c', 'name' => 'tres.com', 'is_active' => true, 'provisioning_type' => 'REGISTRATION_IN_PROGRESS']);

    Livewire::test(ResumenDominios::class)
        ->assertViewHas('total', 3)
        ->assertViewHas('porVencer', 1)
        ->assertViewHas('enTransferencia', 1);
});
