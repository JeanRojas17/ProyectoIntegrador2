@extends('layouts.app')

@section('title', 'Detalle del vehículo')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Vehículo: {{ $vehiculo->placa }}</h1>
        <div class="d-flex gap-2">
            @if (! auth()->user()->esConsulta())
                <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
            @endif
            <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Placa</dt>
                <dd class="col-sm-9">{{ $vehiculo->placa }}</dd>

                <dt class="col-sm-3">Marca / Modelo</dt>
                <dd class="col-sm-9">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</dd>

                <dt class="col-sm-3">Año</dt>
                <dd class="col-sm-9">{{ $vehiculo->anio }}</dd>

                <dt class="col-sm-3">Tipo</dt>
                <dd class="col-sm-9">{{ $vehiculo->tipo_vehiculo }}</dd>

                <dt class="col-sm-3">Capacidad de carga</dt>
                <dd class="col-sm-9">{{ number_format($vehiculo->capacidad_carga, 2) }} kg</dd>

                <dt class="col-sm-3">Estado</dt>
                <dd class="col-sm-9 text-capitalize">{{ str_replace('_', ' ', $vehiculo->estado) }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Envíos realizados</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Código</th><th>Fecha</th><th>Ruta</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @forelse ($vehiculo->envios as $envio)
                        <tr>
                            <td><a href="{{ route('envios.show', $envio) }}">{{ $envio->codigo }}</a></td>
                            <td>{{ $envio->fecha_envio->format('d/m/Y') }}</td>
                            <td>{{ $envio->ruta?->origen }} → {{ $envio->ruta?->destino }}</td>
                            <td class="text-capitalize">{{ str_replace('_', ' ', $envio->estado) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Sin envíos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
