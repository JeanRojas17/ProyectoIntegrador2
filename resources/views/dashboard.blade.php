@extends('layouts.app')

@section('title', 'Panel de control')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Panel de control</h1>
        <span class="text-muted">Resumen general del sistema</span>
    </div>

    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label' => 'Clientes', 'value' => $totales['clientes'], 'route' => 'clientes.index', 'icon' => 'people', 'color' => 'primary'],
                ['label' => 'Conductores', 'value' => $totales['conductores'], 'route' => 'conductores.index', 'icon' => 'person-badge', 'color' => 'success'],
                ['label' => 'Vehículos', 'value' => $totales['vehiculos'], 'route' => 'vehiculos.index', 'icon' => 'truck', 'color' => 'warning'],
                ['label' => 'Rutas', 'value' => $totales['rutas'], 'route' => 'rutas.index', 'icon' => 'signpost-split', 'color' => 'info'],
                ['label' => 'Envíos', 'value' => $totales['envios'], 'route' => 'envios.index', 'icon' => 'box-seam', 'color' => 'danger'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-6 col-md-4 col-xl">
                <a href="{{ route($card['route']) }}" class="text-decoration-none">
                    <div class="card card-stat shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small text-uppercase">{{ $card['label'] }}</div>
                                    <div class="fs-3 fw-bold text-dark">{{ $card['value'] }}</div>
                                </div>
                                <i class="bi bi-{{ $card['icon'] }} fs-1 text-{{ $card['color'] }} opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Envíos por estado</div>
                <ul class="list-group list-group-flush">
                    @forelse ($enviosPorEstado as $estado => $total)
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-capitalize">{{ str_replace('_', ' ', $estado) }}</span>
                            <span class="badge bg-secondary rounded-pill">{{ $total }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Sin datos</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                    <span>Últimos envíos</span>
                    <a href="{{ route('envios.index') }}" class="small">Ver todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Ruta</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ultimosEnvios as $envio)
                                <tr>
                                    <td><a href="{{ route('envios.show', $envio) }}">{{ $envio->codigo }}</a></td>
                                    <td>{{ $envio->cliente->nombre }}</td>
                                    <td>{{ $envio->ruta->origen }} → {{ $envio->ruta->destino }}</td>
                                    <td><span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $envio->estado) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No hay envíos registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
