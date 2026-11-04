<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoloLecturaTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_consulta_puede_ver_listados_y_reportes(): void
    {
        $consulta = User::factory()->create(['rol' => 'consulta']);

        $this->actingAs($consulta)->get(route('clientes.index'))->assertOk();
        $this->actingAs($consulta)->get(route('reportes.index'))->assertOk();
        $this->actingAs($consulta)->get(route('reportes.exportar'))->assertOk();
    }

    public function test_usuario_consulta_no_puede_crear_editar_ni_eliminar(): void
    {
        $consulta = User::factory()->create(['rol' => 'consulta']);
        $cliente = Cliente::create([
            'tipo_documento' => 'CC',
            'numero_documento' => '123456',
            'nombre' => 'Cliente de prueba',
            'telefono' => '3001234567',
        ]);

        $this->actingAs($consulta)->get(route('clientes.create'))->assertForbidden();
        $this->actingAs($consulta)->get(route('clientes.edit', $cliente))->assertForbidden();
        $this->actingAs($consulta)->post(route('clientes.store'), [])->assertForbidden();
        $this->actingAs($consulta)->put(route('clientes.update', $cliente), [])->assertForbidden();
        $this->actingAs($consulta)->delete(route('clientes.destroy', $cliente))->assertForbidden();
    }

    public function test_administrador_conserva_acceso_a_crear_y_editar(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $cliente = Cliente::create([
            'tipo_documento' => 'CC',
            'numero_documento' => '654321',
            'nombre' => 'Cliente de prueba',
            'telefono' => '3007654321',
        ]);

        $this->actingAs($admin)->get(route('clientes.create'))->assertOk();
        $this->actingAs($admin)->get(route('clientes.edit', $cliente))->assertOk();
    }
}
