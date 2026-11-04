@extends('layouts.app')

@section('title', 'Detalle de la ruta')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Ruta: {{ $ruta->origen }} → {{ $ruta->destino }}</h1>
        <div class="d-flex gap-2">
            @if (! auth()->user()->esConsulta())
                <a href="{{ route('rutas.edit', $ruta) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
            @endif
            <a href="{{ route('rutas.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Origen</dt>
                <dd class="col-sm-9">{{ $ruta->origen }}</dd>

                <dt class="col-sm-3">Destino</dt>
                <dd class="col-sm-9">{{ $ruta->destino }}</dd>

                <dt class="col-sm-3">Distancia</dt>
                <dd class="col-sm-9">{{ number_format($ruta->distancia_km, 2) }} km</dd>

                <dt class="col-sm-3">Tiempo estimado</dt>
                <dd class="col-sm-9">{{ $ruta->tiempo_estimado_horas ? $ruta->tiempo_estimado_horas . ' horas' : '—' }}</dd>

                <dt class="col-sm-3">Tarifa base</dt>
                <dd class="col-sm-9">${{ number_format($ruta->tarifa_base, 2) }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Envíos en esta ruta</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Código</th><th>Fecha</th><th>Cliente</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @forelse ($ruta->envios as $envio)
                        <tr>
                            <td><a href="{{ route('envios.show', $envio) }}">{{ $envio->codigo }}</a></td>
                            <td>{{ $envio->fecha_envio->format('d/m/Y') }}</td>
                            <td>{{ $envio->cliente?->nombre }}</td>
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
