<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 10)->default('CC');
            $table->string('numero_documento', 20)->unique();
            $table->string('nombre', 150);
            $table->string('telefono', 20);
            $table->string('email', 150)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
