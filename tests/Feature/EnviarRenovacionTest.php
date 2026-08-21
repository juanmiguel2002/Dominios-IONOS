<?php

use App\Mail\RenovacionDominio;
use App\Models\DomainNotice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function fakeIonosParaRenovacion(string $expira): void
{
    Http::fake([
        '*/domainitems/domains*' => Http::response([
            'domains' => [[
                'id' => 'dom-1',
                'name' => 'ejemplo.com',
                'provisioningStatus' => ['setToExpireOn' => $expira],
            ]],
        ], 200),
        '*/contacts*' => Http::response([
            'registrant' => ['email' => 'titular@ejemplo.com'],
        ], 200),
    ]);
}

it('envía el aviso a 30 días y registra el DomainNotice', function () {
    Mail::fake();
    fakeIonosParaRenovacion(now()->addDays(30)->toDateString());

    $this->artisan('renovacion:cron')->assertSuccessful();

    Mail::assertSent(RenovacionDominio::class, 1);
    expect(DomainNotice::where('domain', 'ejemplo.com')->where('type', 'aviso')->count())->toBe(1);
});

it('no reenvía el aviso en una segunda ejecución (idempotente)', function () {
    Mail::fake();
    fakeIonosParaRenovacion(now()->addDays(30)->toDateString());

    $this->artisan('renovacion:cron')->assertSuccessful();
    $this->artisan('renovacion:cron')->assertSuccessful();

    Mail::assertSent(RenovacionDominio::class, 1); // solo una vez
    expect(DomainNotice::where('domain', 'ejemplo.com')->where('type', 'aviso')->count())->toBe(1);
});

it('no envía nada cuando faltan más de 30 días', function () {
    Mail::fake();
    fakeIonosParaRenovacion(now()->addDays(90)->toDateString());

    $this->artisan('renovacion:cron')->assertSuccessful();

    Mail::assertNothingSent();
    expect(DomainNotice::count())->toBe(0);
});
