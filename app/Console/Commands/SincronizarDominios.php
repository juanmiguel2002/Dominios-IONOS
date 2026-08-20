<?php

namespace App\Console\Commands;

use App\Jobs\SincronizarDominios as SincronizarDominiosJob;
use Illuminate\Console\Command;

class SincronizarDominios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dominios:sincronizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza el catálogo de dominios de IONOS con la tabla local.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sincronizando dominios con IONOS...');

        try {
            $resultado = app()->call([new SincronizarDominiosJob(), 'handle']);
        } catch (\Throwable $e) {
            $this->error('Error en la sincronización: ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            '✅ Sincronización completada: %d creados, %d actualizados, %d marcados inactivos.',
            $resultado['creados'],
            $resultado['actualizados'],
            $resultado['inactivos'],
        ));

        return self::SUCCESS;
    }
}
