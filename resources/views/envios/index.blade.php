@extends('layouts.app')

@section('title', 'Envíos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Envíos</h1>
        @if (! auth()->user()->esConsulta())
            <a href="{{ route('envios.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo envío
            </a>
        @endif
    </div>

    {{-- Filtros de búsqueda --}}
    <form method="GET" action="{{ route('envios.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente"   {{ request('estado') == 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_transito" {{ request('estado') == 'en_transito' ? 'selected' : '' }}>En tránsito</option>
                    <option value="entregado"   {{ request('estado') == 'entregado'   ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelado"   {{ request('estado') == 'cancelado'   ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="col-md-4">
                <select name="ruta_id" class="form-select">
                    <option value="">Todas las rutas</option>
                    @foreach($rutas as $ruta)
                        <option value="{{ $ruta->id }}" {{ request('ruta_id') == $ruta->id ? 'selected' : '' }}>
                            {{ $ruta->origen }} - {{ $ruta->destino }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
                @if(request('estado') || request('ruta_id'))
                    <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary">
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
                            <td>
                                <span class="badge bg-secondary text-capitalize">
                                    {{ str_replace('_', ' ', $envio->estado) }}
                                </span>
                            </td>
                            <td class="table-actions text-end">
                                <a href="{{ route('envios.show', $envio) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if (! auth()->user()->esConsulta())
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
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay envíos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $envios->links() }}</div>
@endsection