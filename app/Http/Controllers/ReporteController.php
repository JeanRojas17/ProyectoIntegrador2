<?php

namespace App\Http\Controllers;

use App\Models\Envio;
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

        return view('reportes.index', compact('porEstado', 'porRuta'));
    }

    public function exportar()
    {
        $envios = Envio::with(['cliente', 'ruta'])->get();

        $csv = "Codigo,Cliente,Ruta,Estado,peso_kg\n";

        foreach ($envios as $envio) {
            $csv .= "{$envio->codigo},{$envio->cliente->nombre},{$envio->ruta->origen}-{$envio->ruta->destino},{$envio->estado},{$envio->peso_kg}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="envios.csv"');
    }
}