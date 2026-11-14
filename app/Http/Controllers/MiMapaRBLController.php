<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\View\View;

class MiMapaRBLController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $rutas = Ruta::query()
            ->when($user->esConductor(), function ($query) use ($user) {
                $query->whereHas('envios', fn ($envios) => $envios->where('conductor_id', $user->conductor?->id ?? 0));
            })
            ->orderBy('origen')
            ->get();

        return view('mimaparbl.index', compact('rutas'));
    }
}
