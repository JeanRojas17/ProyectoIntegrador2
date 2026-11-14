<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConductorRequest;
use App\Models\Conductor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConductorController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = $request->input('buscar');

        $conductores = Conductor::when($buscar, function ($query, $buscar) {
                $query->where('nombres', 'like', "%{$buscar}%")
                      ->orWhere('apellidos', 'like', "%{$buscar}%")
                      ->orWhere('numero_documento', 'like', "%{$buscar}%")
                      ->orWhere('numero_licencia', 'like', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('conductores.index', compact('conductores'));
    }

    public function create(): View
    {
        return view('conductores.create');
    }

    public function store(ConductorRequest $request): RedirectResponse
    {
        Conductor::create($request->validated());

        return redirect()->route('conductores.index')
            ->with('success', 'Conductor registrado correctamente.');
    }

    public function show(Conductor $conductor): View
    {
        $conductor->load('envios');

        return view('conductores.show', compact('conductor'));
    }

    public function edit(Conductor $conductor): View
    {
        return view('conductores.edit', compact('conductor'));
    }

    public function update(ConductorRequest $request, Conductor $conductor): RedirectResponse
    {
        $conductor->update($request->validated());

        return redirect()->route('conductores.index')
            ->with('success', 'Conductor actualizado correctamente.');
    }

    public function destroy(Conductor $conductor): RedirectResponse
    {
        $conductor->delete();

        return redirect()->route('conductores.index')
            ->with('success', 'Conductor eliminado correctamente.');
    }
}
