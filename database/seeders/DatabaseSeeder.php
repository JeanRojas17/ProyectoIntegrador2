<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Conductor;
use App\Models\Envio;
use App\Models\Ruta;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@transportesrbl.com'],
            [
                'name' => 'Administrador RBL',
                'password' => Hash::make('password'),
            ]
        );

        if (Cliente::count() > 0) {
            return;
        }

        $clientes = collect([
            ['tipo_documento' => 'NIT', 'numero_documento' => '900123456-1', 'nombre' => 'Distribuidora El Progreso S.A.S.', 'telefono' => '3104567890', 'email' => 'compras@elprogreso.com', 'direccion' => 'Calle 45 # 12-30, Bogotá'],
            ['tipo_documento' => 'CC', 'numero_documento' => '1020304050', 'nombre' => 'María Fernanda Gómez', 'telefono' => '3209876543', 'email' => 'maria.gomez@correo.com', 'direccion' => 'Carrera 8 # 20-15, Medellín'],
            ['tipo_documento' => 'NIT', 'numero_documento' => '901987654-2', 'nombre' => 'Alimentos del Norte Ltda.', 'telefono' => '6053214567', 'email' => 'logistica@alimentosnorte.com', 'direccion' => 'Vía 40 # 65-10, Barranquilla'],
            ['tipo_documento' => 'CC', 'numero_documento' => '79456123', 'nombre' => 'Carlos Andrés Ramírez', 'telefono' => '3157894561', 'email' => 'carlos.ramirez@correo.com', 'direccion' => 'Avenida 6N # 23-45, Cali'],
            ['tipo_documento' => 'NIT', 'numero_documento' => '800456789-3', 'nombre' => 'Ferretería Central S.A.', 'telefono' => '3006549871', 'email' => 'pedidos@ferreteriacentral.com', 'direccion' => 'Calle 80 # 50-22, Bucaramanga'],
        ])->map(fn (array $data) => Cliente::create($data));

        $conductores = collect([
            ['tipo_documento' => 'CC', 'numero_documento' => '71234567', 'nombres' => 'Jorge Luis', 'apellidos' => 'Martínez Rojas', 'telefono' => '3114567890', 'numero_licencia' => 'LIC-001234', 'categoria_licencia' => 'C3', 'fecha_vencimiento_licencia' => '2027-05-15', 'estado' => 'disponible'],
            ['tipo_documento' => 'CC', 'numero_documento' => '79845612', 'nombres' => 'Andrés Felipe', 'apellidos' => 'Cárdenas Peña', 'telefono' => '3125678901', 'numero_licencia' => 'LIC-005678', 'categoria_licencia' => 'C2', 'fecha_vencimiento_licencia' => '2026-11-30', 'estado' => 'en_ruta'],
            ['tipo_documento' => 'CC', 'numero_documento' => '1098765432', 'nombres' => 'Luis Alberto', 'apellidos' => 'Torres Vega', 'telefono' => '3136789012', 'numero_licencia' => 'LIC-009012', 'categoria_licencia' => 'C3', 'fecha_vencimiento_licencia' => '2028-02-20', 'estado' => 'disponible'],
            ['tipo_documento' => 'CE', 'numero_documento' => 'E1234567', 'nombres' => 'Pedro José', 'apellidos' => 'Salazar Mora', 'telefono' => '3147890123', 'numero_licencia' => 'LIC-003456', 'categoria_licencia' => 'C2', 'fecha_vencimiento_licencia' => '2027-08-10', 'estado' => 'en_ruta'],
            ['tipo_documento' => 'CC', 'numero_documento' => '1023456789', 'nombres' => 'Diego Armando', 'apellidos' => 'Herrera Ruiz', 'telefono' => '3158901234', 'numero_licencia' => 'LIC-007890', 'categoria_licencia' => 'C3', 'fecha_vencimiento_licencia' => '2029-01-05', 'estado' => 'inactivo'],
        ])->map(fn (array $data) => Conductor::create($data));

        $vehiculos = collect([
            ['placa' => 'ABC123', 'marca' => 'Chevrolet', 'modelo' => 'NPR', 'anio' => 2021, 'tipo_vehiculo' => 'Camión', 'capacidad_carga' => 4500, 'estado' => 'disponible'],
            ['placa' => 'DEF456', 'marca' => 'Hino', 'modelo' => 'Dutro', 'anio' => 2022, 'tipo_vehiculo' => 'Camión', 'capacidad_carga' => 6000, 'estado' => 'en_ruta'],
            ['placa' => 'GHI789', 'marca' => 'Renault', 'modelo' => 'Kangoo', 'anio' => 2020, 'tipo_vehiculo' => 'Furgón', 'capacidad_carga' => 1500, 'estado' => 'mantenimiento'],
            ['placa' => 'JKL012', 'marca' => 'Mercedes-Benz', 'modelo' => 'Accelo', 'anio' => 2023, 'tipo_vehiculo' => 'Camión', 'capacidad_carga' => 8000, 'estado' => 'disponible'],
            ['placa' => 'MNO345', 'marca' => 'Toyota', 'modelo' => 'Hilux', 'anio' => 2019, 'tipo_vehiculo' => 'Camioneta', 'capacidad_carga' => 1000, 'estado' => 'inactivo'],
        ])->map(fn (array $data) => Vehiculo::create($data));

        $rutas = collect([
            ['origen' => 'Bogotá', 'destino' => 'Medellín', 'distancia_km' => 415, 'tiempo_estimado_horas' => 8.5, 'tarifa_base' => 850000],
            ['origen' => 'Bogotá', 'destino' => 'Cali', 'distancia_km' => 460, 'tiempo_estimado_horas' => 9.0, 'tarifa_base' => 920000],
            ['origen' => 'Medellín', 'destino' => 'Barranquilla', 'distancia_km' => 700, 'tiempo_estimado_horas' => 13.0, 'tarifa_base' => 1350000],
            ['origen' => 'Cali', 'destino' => 'Bucaramanga', 'distancia_km' => 780, 'tiempo_estimado_horas' => 15.0, 'tarifa_base' => 1480000],
            ['origen' => 'Barranquilla', 'destino' => 'Cartagena', 'distancia_km' => 120, 'tiempo_estimado_horas' => 2.5, 'tarifa_base' => 320000],
        ])->map(fn (array $data) => Ruta::create($data));

        $envios = [
            ['codigo' => 'ENV-2026-001', 'fecha_envio' => '2026-10-15', 'descripcion_carga' => 'Cajas de alimentos no perecederos', 'peso_kg' => 3200, 'valor_declarado' => 4500000, 'estado' => 'entregado'],
            ['codigo' => 'ENV-2026-002', 'fecha_envio' => '2026-10-18', 'descripcion_carga' => 'Electrodomésticos', 'peso_kg' => 1800, 'valor_declarado' => 7800000, 'estado' => 'en_transito'],
            ['codigo' => 'ENV-2026-003', 'fecha_envio' => '2026-10-20', 'descripcion_carga' => 'Materiales de ferretería', 'peso_kg' => 5400, 'valor_declarado' => 3200000, 'estado' => 'pendiente'],
            ['codigo' => 'ENV-2026-004', 'fecha_envio' => '2026-10-22', 'descripcion_carga' => 'Productos de aseo', 'peso_kg' => 950, 'valor_declarado' => 1200000, 'estado' => 'entregado'],
            ['codigo' => 'ENV-2026-005', 'fecha_envio' => '2026-10-25', 'descripcion_carga' => 'Repuestos automotrices', 'peso_kg' => 2100, 'valor_declarado' => 5600000, 'estado' => 'en_transito'],
            ['codigo' => 'ENV-2026-006', 'fecha_envio' => '2026-10-28', 'descripcion_carga' => 'Muebles de oficina', 'peso_kg' => 1400, 'valor_declarado' => 2900000, 'estado' => 'cancelado'],
            ['codigo' => 'ENV-2026-007', 'fecha_envio' => '2026-11-01', 'descripcion_carga' => 'Textiles y confecciones', 'peso_kg' => 700, 'valor_declarado' => 1800000, 'estado' => 'pendiente'],
            ['codigo' => 'ENV-2026-008', 'fecha_envio' => '2026-11-02', 'descripcion_carga' => 'Bebidas embotelladas', 'peso_kg' => 4100, 'valor_declarado' => 2300000, 'estado' => 'en_transito'],
        ];

        foreach ($envios as $i => $data) {
            Envio::create(array_merge($data, [
                'cliente_id' => $clientes[$i % $clientes->count()]->id,
                'conductor_id' => $conductores[$i % $conductores->count()]->id,
                'vehiculo_id' => $vehiculos[$i % $vehiculos->count()]->id,
                'ruta_id' => $rutas[$i % $rutas->count()]->id,
            ]));
        }
    }
}
