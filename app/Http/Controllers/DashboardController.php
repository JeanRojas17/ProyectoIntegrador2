<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Conductor;
use App\Models\Envio;
use App\Models\Ruta;
use App\Models\Vehiculo;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totales = [
            'clientes' => Cliente::count(),
            'conductores' => Conductor::count(),
            'vehiculos' => Vehiculo::count(),
            'rutas' => Ruta::count(),
            'envios' => Envio::count(),
        ];

        $enviosPorEstado = Envio::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $ultimosEnvios = Envio::with(['cliente', 'ruta'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('totales', 'enviosPorEstado', 'ultimosEnvios'));
    }
}
