<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('rutas', 'tarifa_base')) {
            Schema::table('rutas', function (Blueprint $table) {
                $table->dropColumn('tarifa_base');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('rutas', 'tarifa_base')) {
            Schema::table('rutas', function (Blueprint $table) {
                $table->decimal('tarifa_base', 12, 2)->nullable();
            });
        }
    }
};
