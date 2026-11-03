<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnvioRequest;
use App\Models\Cliente;
use App\Models\Conductor;
use App\Models\Envio;
use App\Models\Ruta;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
  public function index(Request $request)
{
    $estado = $request->input('estado');
    $rutaId = $request->input('ruta_id');

    $envios = Envio::with(['cliente', 'conductor', 'vehiculo', 'ruta'])
        ->when($estado, fn ($q, $estado) => $q->where('estado', $estado))
        ->when($rutaId, fn ($q, $rutaId) => $q->where('ruta_id', $rutaId))
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // Pasar las rutas para el select del filtro
    $rutas = \App\Models\Ruta::orderBy('origen')->get();

    return view('envios.index', compact('envios', 'rutas'));
}

    public function create(): View
    {
        return view('envios.create', $this->formData());
    }

    public function store(EnvioRequest $request): RedirectResponse
    {
        Envio::create($request->validated());

        return redirect()->route('envios.index')
            ->with('success', 'Envío registrado correctamente.');
    }

    public function show(Envio $envio): View
    {
        $envio->load(['cliente', 'conductor', 'vehiculo', 'ruta']);

        return view('envios.show', compact('envio'));
    }

    public function edit(Envio $envio): View
    {
        return view('envios.edit', array_merge(
            ['envio' => $envio],
            $this->formData()
        ));
    }

    public function update(EnvioRequest $request, Envio $envio): RedirectResponse
    {
        $envio->update($request->validated());

        return redirect()->route('envios.index')
            ->with('success', 'Envío actualizado correctamente.');
    }

    public function destroy(Envio $envio): RedirectResponse
    {
        $envio->delete();

        return redirect()->route('envios.index')
            ->with('success', 'Envío eliminado correctamente.');
    }

    private function formData(): array
    {
        return [
            'clientes' => Cliente::orderBy('nombre')->get(),
            'conductores' => Conductor::orderBy('nombres')->get(),
            'vehiculos' => Vehiculo::orderBy('placa')->get(),
            'rutas' => Ruta::orderBy('origen')->get(),
        ];
    }
}
