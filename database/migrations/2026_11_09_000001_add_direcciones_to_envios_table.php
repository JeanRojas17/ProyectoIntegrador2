<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->string('direccion_origen')->nullable()->after('ruta_id');
            $table->string('direccion_destino')->nullable()->after('direccion_origen');
        });
    }

    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropColumn(['direccion_origen', 'direccion_destino']);
        });
    }
};
