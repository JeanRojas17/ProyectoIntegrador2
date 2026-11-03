<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Ruta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        $porEstado = Envio::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();

        $porRuta = Envio::with('ruta')
            ->selectRaw('ruta_id, COUNT(*) as total, SUM(peso_kg) as peso_total')
            ->groupBy('ruta_id')
            ->get();

        $rutas = Ruta::orderBy('origen')->get();

        return view('reportes.index', compact('porEstado', 'porRuta', 'rutas'));
    }

    public function exportar(Request $request)
    {
        $query = Envio::with(['cliente', 'ruta']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('ruta_id')) {
            $query->where('ruta_id', $request->ruta_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
        }

        $envios = $query->orderBy('fecha_envio')->get();

        $filtros = $request->only(['estado', 'ruta_id', 'fecha_desde', 'fecha_hasta']);
        $rutaSeleccionada = $request->filled('ruta_id') ? Ruta::find($request->ruta_id) : null;

        $pdf = Pdf::loadView('reportes.pdf', compact('envios', 'filtros', 'rutaSeleccionada'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('envios.pdf');
    }
}