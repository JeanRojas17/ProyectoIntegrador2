<?php

namespace App\Http\Controllers;

use App\Http\Requests\RutaRequest;
use App\Models\Ruta;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RutaController extends Controller
{
    public function index(): View
    {
        $rutas = Ruta::latest()->paginate(10);

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
        $ruta->load('envios');

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
