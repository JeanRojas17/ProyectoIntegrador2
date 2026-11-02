@extends('layouts.app')

@section('title', 'Envíos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Envíos</h1>
        <a href="{{ route('envios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo envío
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Ruta</th>
                        <th>Conductor</th>
                        <th>Vehículo</th>
                        <th>Estado</th>
                        <th class="table-actions text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($envios as $envio)
                        <tr>
                            <td class="fw-semibold">{{ $envio->codigo }}</td>
                            <td>{{ $envio->fecha_envio->format('d/m/Y') }}</td>
                            <td>{{ $envio->cliente?->nombre }}</td>
                            <td>{{ $envio->ruta?->origen }} → {{ $envio->ruta?->destino }}</td>
                            <td>{{ $envio->conductor?->nombres }} {{ $envio->conductor?->apellidos }}</td>
                            <td>{{ $envio->vehiculo?->placa }}</td>
                            <td><span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $envio->estado) }}</span></td>
                            <td class="table-actions text-end">
                                <a href="{{ route('envios.show', $envio) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('envios.edit', $envio) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('envios.destroy', $envio) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este envío?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No hay envíos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $envios->links() }}</div>
@endsection
