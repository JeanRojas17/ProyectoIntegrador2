<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruta extends Model
{
    use HasFactory;

    protected $table = 'rutas';

    protected $fillable = [
        'origen',
        'destino',
        'distancia_km',
        'tiempo_estimado_horas',
        'tarifa_base',
    ];

    public function envios(): HasMany
    {
        return $this->hasMany(Envio::class);
    }
}
