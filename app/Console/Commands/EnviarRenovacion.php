<?php

namespace App\Console\Commands;

use App\Mail\DominioRenovado;
use App\Mail\RenovacionDominio;
use App\Models\DomainNotice;
use App\Services\IonosService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarRenovacion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renovacion:cron {--dias=30 : Días de antelación del aviso de renovación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía el aviso de renovación cuando el dominio entra en el umbral de días y la confirmación el día de la renovación (idempotente).';

    /**
     * Execute the console command.
     */
    public function handle(IonosService $ionos): int
    {
        $this->info('Ejecutando revisión de dominios...');

        $umbral = max(0, (int) $this->option('dias'));

        try {
            $dominios = $ionos->obtenerDominios();
        } catch (\Throwable $e) {
            $this->error('Error obteniendo dominios: '.$e->getMessage());

            return self::FAILURE;
        }

        foreach ($dominios as $dominio) {
            $nombre = $dominio['name'] ?? null;

            if (! $nombre) {
                continue;
            }

            $fechaRaw = $dominio['provisioningStatus']['setToExpireOn']
                ?? $dominio['provisioningStatus']['setToRenewOn']
                ?? null;

            if (! $fechaRaw) {
                $this->warn("Dominio {$nombre} no tiene fecha de renovación.");

                continue;
            }

            $fecha = Carbon::parse($fechaRaw);
            $diasRestantes = now()->startOfDay()->diffInDays($fecha->copy()->startOfDay(), false);

            $this->line("Dominio {$nombre} - Renovación: {$fecha->toDateString()} - Días restantes: {$diasRestantes}");

            // Aviso previo: se envía una única vez cuando el dominio entra en el umbral.
            if ($diasRestantes >= 0 && $diasRestantes <= $umbral) {
                $this->enviarUnaVez($ionos, $dominio, $nombre, $fecha, DomainNotice::TYPE_AVISO, $diasRestantes);
            }

            // Confirmación el día exacto de la renovación (una única vez por ciclo).
            if ($diasRestantes === 0) {
                $this->enviarUnaVez($ionos, $dominio, $nombre, $fecha, DomainNotice::TYPE_RENOVADO, $diasRestantes);
            }

            if ($diasRestantes < 0) {
                $this->warn("⚠️ Dominio {$nombre} ya expiró ({$fecha->toDateString()}).");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Envía el correo del tipo indicado solo si no se ha enviado ya para este dominio y ciclo.
     */
    protected function enviarUnaVez(
        IonosService $ionos,
        array $dominio,
        string $nombre,
        Carbon $fecha,
        string $tipo,
        int $diasRestantes
    ): void {
        $referenceDate = $fecha->toDateString();

        $yaEnviado = DomainNotice::where('domain', $nombre)
            ->where('type', $tipo)
            ->whereDate('reference_date', $referenceDate)
            ->exists();

        if ($yaEnviado) {
            $this->line("↪️  {$nombre}: aviso '{$tipo}' ya enviado para {$referenceDate}, se omite.");

            return;
        }

        try {
            $contacto = $ionos->obtenerContactoDominio($dominio['id'] ?? null);
            $emailTitular = $contacto['email'] ?? '';

            $mail = Mail::to(config('dominios.notificaciones.to'))
                ->bcc(config('dominios.notificaciones.bcc'));

            if (! empty($emailTitular)) {
                $mail->cc($emailTitular);
            }

            $mailable = $tipo === DomainNotice::TYPE_RENOVADO
                ? new DominioRenovado($nombre, $fecha)
                : new RenovacionDominio($nombre, $fecha, $diasRestantes);

            $mail->send($mailable);

            DomainNotice::create([
                'domain' => $nombre,
                'domain_id' => $dominio['id'] ?? null,
                'type' => $tipo,
                'reference_date' => $referenceDate,
                'notified_at' => now(),
            ]);

            $this->info("✅ {$nombre}: correo '{$tipo}' enviado".($emailTitular ? " (titular: {$emailTitular})." : '.'));
        } catch (\Throwable $e) {
            // No se registra el aviso, de modo que se reintentará en la próxima ejecución.
            $this->error("❌ {$nombre}: error enviando '{$tipo}': ".$e->getMessage());
        }
    }
}
