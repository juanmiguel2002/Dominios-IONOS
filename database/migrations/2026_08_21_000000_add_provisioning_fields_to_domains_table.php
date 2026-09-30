<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            if (! Schema::hasColumn('domains', 'tld')) {
                $table->string('tld')->nullable()->after('name');
            }
            if (! Schema::hasColumn('domains', 'provisioning_type')) {
                $table->string('provisioning_type')->nullable()->after('provisioning_status');
            }
            if (! Schema::hasColumn('domains', 'created_date')) {
                $table->date('created_date')->nullable()->after('set_to_expire_on');
            }
        });
    }

    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $columnas = array_filter(
                ['tld', 'provisioning_type', 'created_date'],
                fn ($c) => Schema::hasColumn('domains', $c)
            );

            if ($columnas !== []) {
                $table->dropColumn($columnas);
            }
        });
    }
};
