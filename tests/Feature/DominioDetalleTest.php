<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('muestra un aviso en lugar de un error 500 si IONOS falla', function () {
    Http::fake(['api.hosting.ionos.com/*' => Http::response(['message' => 'Unauthorized'], 401)]);

    $this->actingAs(User::factory()->create())
        ->get('/dominio/abc')
        ->assertOk()
        ->assertSee('No se pudo obtener la información del dominio');
});

it('no deja ver el detalle a usuarios sin email verificado', function () {
    $this->actingAs(User::factory()->unverified()->create())
        ->get('/dominio/abc')
        ->assertRedirect('/verify-email');
});
