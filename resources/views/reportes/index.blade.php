@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Reportes de Envíos</h1>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Exportar a PDF</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reportes.exportar') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        @foreach (['pendiente' => 'Pendiente', 'en_transito' => 'En tránsito', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado'] as $valor => $texto)
                            <option value="{{ $valor }}" @selected(request('estado') === $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ruta</label>
                    <select name="ruta_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach ($rutas as $ruta)
                            <option value="{{ $ruta->id }}" @selected((string) request('ruta_id') === (string) $ruta->id)>{{ $ruta->origen }} → {{ $ruta->destino }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        {{-- Tabla por Estado --}}
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Envíos por Estado</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Estado</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($porEstado as $item)
                                <tr>
                                    <td>
                                        <span class="badge 
                                            @if($item->estado == 'pendiente') bg-warning text-dark
                                            @elseif($item->estado == 'en_transito') bg-primary
                                            @elseif($item->estado == 'entregado') bg-success
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $item->estado)) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">{{ $item->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No hay datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tabla por Ruta --}}
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Envíos por Ruta</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ruta</th>
                                <th class="text-end">Total envíos</th>
                                <th class="text-end">Peso total (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($porRuta as $item)
                                <tr>
                                    <td>
                                        @if($item->ruta)
                                            {{ $item->ruta->origen }} → {{ $item->ruta->destino }}
                                        @else
                                            Sin ruta
                                        @endif
                                    </td>
                                    <td class="text-end">{{ $item->total }}</td>
                                    <td class="text-end">{{ number_format($item->peso_total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No hay datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection