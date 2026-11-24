<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Conductor;
use App\Models\Ruta;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CapacidadVehiculoTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_permite_asignar_un_volumen_que_supera_la_capacidad_del_vehiculo(): void
    {
        $this->seed();

        $vehiculo = Vehiculo::first();
        $vehiculo->update(['capacidad_carga' => 10]);

        $this->actingAs(User::where('rol', 'administrador')->first())
            ->post(route('envios.store'), $this->datos($vehiculo, 11))
            ->assertSessionHasErrors('volumen_m3');
    }

    public function test_permite_asignar_un_volumen_dentro_de_la_capacidad_disponible(): void
    {
        $this->seed();

        $vehiculo = Vehiculo::first();
        $vehiculo->update(['capacidad_carga' => 10]);

        $this->actingAs(User::where('rol', 'administrador')->first())
            ->post(route('envios.store'), $this->datos($vehiculo, 9))
            ->assertRedirect(route('envios.index'));

        $this->assertDatabaseHas('envios', ['codigo' => 'ENV-CAP-001', 'volumen_m3' => 9]);
    }

    private function datos(Vehiculo $vehiculo, float $volumen): array
    {
        return [
            'codigo' => 'ENV-CAP-001',
            'cliente_id' => Cliente::first()->id,
            'conductor_id' => Conductor::first()->id,
            'vehiculo_id' => $vehiculo->id,
            'ruta_id' => Ruta::first()->id,
            'direccion_origen' => 'Origen de prueba',
            'direccion_destino' => 'Destino de prueba',
            'fecha_envio' => '2026-11-20',
            'descripcion_carga' => 'Carga de prueba',
            'peso_kg' => 500,
            'volumen_m3' => $volumen,
            'estado' => 'pendiente',
        ];
    }
}
