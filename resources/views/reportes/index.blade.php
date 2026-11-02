@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Reportes de Envíos</h1>
        <a href="{{ route('reportes.exportar') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Exportar CSV
        </a>
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