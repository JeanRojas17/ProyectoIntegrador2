<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'anio',
        'tipo_vehiculo',
        'capacidad_carga',
        'estado',
    ];

    public function envios(): HasMany
    {
        return $this->hasMany(Envio::class);
    }
}
