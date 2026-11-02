@extends('layouts.app')

@section('title', 'Rutas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Rutas</h1>
        <a href="{{ route('rutas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva ruta
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Distancia (km)</th>
                        <th>Tiempo estimado (h)</th>
                        <th>Tarifa base</th>
                        <th class="table-actions text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rutas as $ruta)
                        <tr>
                            <td>{{ $ruta->origen }}</td>
                            <td>{{ $ruta->destino }}</td>
                            <td>{{ number_format($ruta->distancia_km, 2) }}</td>
                            <td>{{ $ruta->tiempo_estimado_horas ?? '—' }}</td>
                            <td>${{ number_format($ruta->tarifa_base, 2) }}</td>
                            <td class="table-actions text-end">
                                <a href="{{ route('rutas.show', $ruta) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('rutas.edit', $ruta) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('rutas.destroy', $ruta) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar esta ruta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No hay rutas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $rutas->links() }}</div>
@endsection
