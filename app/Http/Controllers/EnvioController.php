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
    $user = $request->user();
    $estado = $request->input('estado');
    $rutaId = $request->input('ruta_id');
    $buscar = $request->input('buscar');

    $envios = Envio::with(['cliente', 'conductor', 'vehiculo', 'ruta'])
        ->when($user->esConductor(), fn ($q) => $q->where('conductor_id', $user->conductor?->id ?? 0))
        ->when($user->esCliente(), fn ($q) => $q->where('cliente_id', $user->cliente?->id ?? 0))
        ->when($estado, fn ($q, $estado) => $q->where('estado', $estado))
        ->when($rutaId, fn ($q, $rutaId) => $q->where('ruta_id', $rutaId))
        ->when($buscar, function ($query, $buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', "%{$buscar}%"))
                  ->orWhere('direccion_origen', 'like', "%{$buscar}%")
                  ->orWhere('direccion_destino', 'like', "%{$buscar}%");
            });
        })
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
        $user = auth()->user();

        if ($user->esConductor() && $envio->conductor_id !== $user->conductor?->id) {
            abort(403, 'No puedes consultar envíos que no te pertenecen.');
        }

        if ($user->esCliente() && $envio->cliente_id !== $user->cliente?->id) {
            abort(403, 'No puedes consultar envíos de otro cliente.');
        }

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

    public function actualizarEstado(Request $request, Envio $envio): RedirectResponse
    {
        $user = $request->user();

        if ($user->esConductor() && $envio->conductor_id !== $user->conductor?->id) {
            abort(403, 'Solo puedes actualizar el estado de tus envíos asignados.');
        }

        $data = $request->validate([
            'estado' => ['required', 'in:pendiente,en_transito,entregado,cancelado'],
        ]);

        if ($envio->estado === 'entregado') {
            return back()->withErrors(['estado' => 'Un envío entregado ya no puede cambiar de estado.']);
        }

        $envio->update(['estado' => $data['estado']]);

        return back()->with('success', 'Estado del envío actualizado correctamente.');
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
