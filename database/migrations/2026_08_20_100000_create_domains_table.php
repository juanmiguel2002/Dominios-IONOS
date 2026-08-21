<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();

            // Datos que provienen de IONOS
            $table->string('ionos_id')->nullable()->unique();
            $table->string('name')->index();
            $table->string('tld')->nullable();
            $table->string('provisioning_status')->nullable();
            $table->string('provisioning_type')->nullable(); // p. ej. REGISTRATION_IN_PROGRESS
            $table->date('set_to_renew_on')->nullable();
            $table->date('set_to_expire_on')->nullable();
            $table->date('created_date')->nullable();
            $table->boolean('pending_provisioning')->default(false);
            $table->json('raw')->nullable(); // payload completo de IONOS por si hace falta

            // Datos propios (no vienen de IONOS)
            $table->string('client_name')->nullable();
            $table->text('notes')->nullable();

            // Control de sincronización
            $table->boolean('is_active')->default(true); // false = ya no está en IONOS
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
