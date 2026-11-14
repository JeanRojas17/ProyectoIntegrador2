@extends('layouts.app')

@section('title', 'Vehículos')

@section('content')
    @php($puedeGestionar = auth()->user()->esAdministrador())
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Vehículos</h1>
        @if ($puedeGestionar)
            <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo vehículo
            </a>
        @endif
    </div>

    {{-- Formulario de búsqueda --}}
    <form method="GET" action="{{ route('vehiculos.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text"
                       name="buscar"
                       class="form-control"
                       placeholder="Buscar por placa, marca o modelo..."
                       value="{{ request('buscar') }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                @if(request('buscar'))
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">
                        Limpiar
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Placa</th>
                        <th>Marca / Modelo</th>
                        <th>Año</th>
                        <th>Tipo</th>
                        <th>Capacidad (kg)</th>
                        <th>Estado</th>
                        <th class="table-actions text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehiculos as $vehiculo)
                        <tr>
                            <td class="fw-semibold">{{ $vehiculo->placa }}</td>
                            <td>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</td>
                            <td>{{ $vehiculo->anio }}</td>
                            <td>{{ $vehiculo->tipo_vehiculo }}</td>
                            <td>{{ number_format($vehiculo->capacidad_carga, 2) }}</td>
                            <td><span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $vehiculo->estado) }}</span></td>
                            <td class="table-actions text-end">
                                <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if ($puedeGestionar)
                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este vehículo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No hay vehículos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $vehiculos->links() }}</div>
@endsection
