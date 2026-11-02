<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculoRequest;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    public function index(): View
    {
        $vehiculos = Vehiculo::latest()->paginate(10);

        return view('vehiculos.index', compact('vehiculos'));
    }

    public function create(): View
    {
        return view('vehiculos.create');
    }

    public function store(VehiculoRequest $request): RedirectResponse
    {
        Vehiculo::create($request->validated());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo registrado correctamente.');
    }

    public function show(Vehiculo $vehiculo): View
    {
        $vehiculo->load('envios');

        return view('vehiculos.show', compact('vehiculo'));
    }

    public function edit(Vehiculo $vehiculo): View
    {
        return view('vehiculos.edit', compact('vehiculo'));
    }

    public function update(VehiculoRequest $request, Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->update($request->validated());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->delete();

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado correctamente.');
    }
}
