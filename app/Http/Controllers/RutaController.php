<?php

namespace App\Http\Controllers;

use App\Http\Requests\RutaRequest;
use App\Models\Ruta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RutaController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $buscar = $request->input('buscar');

        $rutas = Ruta::query()
            ->when($user->esConductor(), function ($query) use ($user) {
                $query->whereHas('envios', fn ($envios) => $envios->where('conductor_id', $user->conductor?->id ?? 0));
            })
            ->when($buscar, function ($query, $buscar) {
                $query->where('origen', 'like', "%{$buscar}%")
                      ->orWhere('destino', 'like', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('rutas.index', compact('rutas'));
    }

    public function create(): View
    {
        return view('rutas.create');
    }

    public function store(RutaRequest $request): RedirectResponse
    {
        Ruta::create($request->validated());

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta registrada correctamente.');
    }

    public function show(Ruta $ruta): View
    {
        $user = auth()->user();

        if ($user->esConductor()) {
            $conductorId = $user->conductor?->id;

            abort_unless(
                $conductorId && $ruta->envios()->where('conductor_id', $conductorId)->exists(),
                403,
                'Solo puedes consultar las rutas de tus envíos asignados.'
            );

            $ruta->load(['envios' => fn ($envios) => $envios->where('conductor_id', $conductorId)]);
        } else {
            $ruta->load('envios');
        }

        return view('rutas.show', compact('ruta'));
    }

    public function edit(Ruta $ruta): View
    {
        return view('rutas.edit', compact('ruta'));
    }

    public function update(RutaRequest $request, Ruta $ruta): RedirectResponse
    {
        $ruta->update($request->validated());

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy(Ruta $ruta): RedirectResponse
    {
        $ruta->delete();

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta eliminada correctamente.');
    }
}
