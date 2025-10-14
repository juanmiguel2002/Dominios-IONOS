<?php

namespace App\Console\Commands;

use App\Mail\DominioRenovado;
use Illuminate\Console\Command;
use App\Services\IonosService;
use App\Mail\RenovacionDominio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EnviarRenovaciones extends Command
{
    protected $signature = 'dominios:enviar-renovaciones';
    protected $description = 'Enviar email de renovación 30 días antes de la expiración del dominio';

    public function handle(IonosService $ionos)
    {
        $this->info('Ejecutando revisión de dominios...');

        try {
            $dominios = $ionos->obtenerDominios();

            foreach ($dominios as $dominio) {
                $fechaRenovacion = $dominio['provisioningStatus']['setToExpireOn']
                    ?? $dominio['provisioningStatus']['setToRenewOn']
                    ?? null;

                if (!$fechaRenovacion) {
                    $this->warn("Dominio {$dominio['name']} no tiene fecha de renovación.");
                    continue;
                }

                $fecha = Carbon::parse($fechaRenovacion)->startOfDay();
                $hoy = now()->startOfDay();
                $diasRestantes = $hoy->diffInDays($fecha, false);

                $contact = $ionos->obtenerContactoDominio($dominio['id'] ?? null);
                $emailTitular = $contact['email'] ?? '';


                // Mostrar información de seguimiento
                $this->info("Dominio {$dominio['name']} - Fecha renovación: {$fecha->toDateString()} - Días restantes: {$diasRestantes} - Email titular: {$emailTitular}");
                //$this->info("Dominio: {$dominio['name']} - Fecha renovación: {$fecha->toDateString()} - Días restantes: {$diasRestantes}");

                // Si faltan exactamente 30 días
                if ($diasRestantes === 30) {
                    try {
                        $mail = Mail::to('web@ivarscomagenciadepublicidad.com')->bcc('joseivars@ivarscom.com');

                        if (!empty($emailTitular)) {
                            $mail->cc($emailTitular);
                        }

                        $mail->send(new RenovacionDominio($dominio['name'], $fecha, $diasRestantes));

                        $this->info("✅ Email de renovación enviado para el dominio {$dominio['name']}.");
                    } catch (\Throwable $mailError) {
                        $this->error("❌ Error enviando email para {$dominio['name']}: " . $mailError->getMessage());
                    }
                }

                // 🟩 Día exacto de la renovación → Confirmación de renovación
                if ($diasRestantes === 0) {
                    try {

                        $mail = Mail::to('web@ivarscomagenciadepublicidad.com')->bcc('joseivars@ivarscom.com');

                        if ($emailTitular) {
                            $mail->cc($emailTitular);
                        }

                        $mail->send(new DominioRenovado($dominio['name'], $fecha));

                        $this->info("✅ Confirmación de renovación enviada para {$dominio['name']}.");

                    } catch (\Throwable $mailError) {
                        $this->error("❌ Error enviando confirmación para {$dominio['name']}: " . $mailError->getMessage());
                    }
                }

                // Si el dominio ya expiró
                if ($diasRestantes < 0) {
                    $this->warn("⚠️ Dominio {$dominio['name']} ya expiró ({$fecha->toDateString()}).");
                }
            }

        } catch (\Throwable $e) {
            $this->error("Error general: " . $e->getMessage());
        }
    }

}
