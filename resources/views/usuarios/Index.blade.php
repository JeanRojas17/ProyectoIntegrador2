@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo usuario
        </a>
    </div>

    <form method="GET" action="{{ route('usuarios.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="buscar" class="form-control"
                       placeholder="Buscar por nombre o correo..."
                       value="{{ request('buscar') }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Vínculo</th>
                        <th class="table-actions text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td><span class="badge bg-secondary text-capitalize">{{ $usuario->rol }}</span></td>
                            <td>
                                @if ($usuario->conductor)
                                    Conductor: {{ $usuario->conductor->nombres }} {{ $usuario->conductor->apellidos }}
                                @elseif ($usuario->cliente)
                                    Cliente: {{ $usuario->cliente->nombre }}
                                @else
                                    <span class="text-muted">Sin vínculo</span>
                                @endif
                            </td>
                            <td class="table-actions text-end">
                                <a href="{{ route('usuarios.edit', $usuario) }}"
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if (! $usuario->is(auth()->user()))
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('¿Eliminar este usuario?');">
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
                            <td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $usuarios->links() }}</div>
@endsection