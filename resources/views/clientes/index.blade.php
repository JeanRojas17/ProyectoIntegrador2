@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Clientes</h1>
        @if (! auth()->user()->esConsulta())
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo cliente
            </a>
        @endif
    </div>

    {{-- Formulario de búsqueda (solo clientes) --}}
    <form method="GET" action="{{ route('clientes.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text"
                       name="buscar"
                       class="form-control"
                       placeholder="Buscar por nombre o documento..."
                       value="{{ request('buscar') }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                @if(request('buscar'))
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
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
                        <th>Documento</th>
                        <th>Nombre / Razón social</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th class="table-actions text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>{{ $cliente->email ?? '—' }}</td>
                            <td class="table-actions text-end">
                                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if (! auth()->user()->esConsulta())
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este cliente?');">
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
                            <td colspan="5" class="text-center text-muted py-4">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $clientes->links() }}</div>
@endsection