@extends('layouts.app')

@section('title', 'Conductores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Conductores</h1>
        @if (! auth()->user()->esConsulta())
            <a href="{{ route('conductores.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo conductor
            </a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Documento</th>
                        <th>Nombre completo</th>
                        <th>Teléfono</th>
                        <th>Licencia</th>
                        <th>Estado</th>
                        <th class="table-actions text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($conductores as $conductor)
                        <tr>
                            <td>{{ $conductor->tipo_documento }} {{ $conductor->numero_documento }}</td>
                            <td>{{ $conductor->nombres }} {{ $conductor->apellidos }}</td>
                            <td>{{ $conductor->telefono }}</td>
                            <td>{{ $conductor->numero_licencia }} ({{ $conductor->categoria_licencia }})</td>
                            <td><span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $conductor->estado) }}</span></td>
                            <td class="table-actions text-end">
                                <a href="{{ route('conductores.show', $conductor) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if (! auth()->user()->esConsulta())
                                    <a href="{{ route('conductores.edit', $conductor) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('conductores.destroy', $conductor) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este conductor?');">
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
                        <tr><td colspan="6" class="text-center text-muted py-4">No hay conductores registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $conductores->links() }}</div>
@endsection
