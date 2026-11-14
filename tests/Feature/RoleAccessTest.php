<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_consulta_puede_ver_pero_no_gestionar(): void
    {
        $user = User::factory()->create(['rol' => 'consulta']);

        $this->actingAs($user)->get('/clientes')->assertOk();
        $this->actingAs($user)->get('/clientes/create')->assertForbidden();
        $this->actingAs($user)->get('/conductores')->assertOk();
        $this->actingAs($user)->get('/reportes')->assertOk();
    }

    public function test_conductor_solo_accede_a_envios(): void
    {
        $user = User::factory()->create(['rol' => 'conductor']);

        $this->actingAs($user)->get('/envios')->assertOk();
        $this->actingAs($user)->get('/envios/create')->assertForbidden();
        $this->actingAs($user)->get('/rutas')->assertOk();
        $this->actingAs($user)->get('/rutas/create')->assertForbidden();
        $this->actingAs($user)->get('/mimaparbl')->assertOk();
        $this->actingAs($user)->get('/reportes')->assertForbidden();
        $this->actingAs($user)->get('/clientes')->assertForbidden();
        $this->actingAs($user)->get('/vehiculos')->assertForbidden();
    }

    public function test_auxiliar_solo_accede_al_panel(): void
    {
        $user = User::factory()->create(['rol' => 'auxiliar']);

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/envios')->assertForbidden();
        $this->actingAs($user)->get('/clientes')->assertForbidden();
    }

    public function test_administrador_gestiona(): void
    {
        $user = User::factory()->create(['rol' => 'administrador']);

        $this->actingAs($user)->get('/clientes')->assertOk();
        $this->actingAs($user)->get('/clientes/create')->assertOk();
        $this->actingAs($user)->get('/envios/create')->assertOk();
    }
}
