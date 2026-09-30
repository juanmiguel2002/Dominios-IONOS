<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_notices', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('domain_id')->nullable();
            $table->string('type'); // aviso | renovado
            $table->date('reference_date'); // fecha de expiración/renovación a la que se refiere el aviso
            $table->timestamp('notified_at');
            $table->timestamps();

            // Un mismo aviso, para un mismo dominio y ciclo de vencimiento, solo se envía una vez.
            $table->unique(['domain', 'type', 'reference_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_notices');
    }
};
