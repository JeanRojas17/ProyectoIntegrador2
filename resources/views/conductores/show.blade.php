@extends('layouts.app')

@section('title', 'Detalle del conductor')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Conductor: {{ $conductor->nombres }} {{ $conductor->apellidos }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('conductores.edit', $conductor) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
            <a href="{{ route('conductores.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Documento</dt>
                <dd class="col-sm-9">{{ $conductor->tipo_documento }} {{ $conductor->numero_documento }}</dd>

                <dt class="col-sm-3">Teléfono</dt>
                <dd class="col-sm-9">{{ $conductor->telefono }}</dd>

                <dt class="col-sm-3">Licencia</dt>
                <dd class="col-sm-9">{{ $conductor->numero_licencia }} — Categoría {{ $conductor->categoria_licencia }}</dd>

                <dt class="col-sm-3">Vencimiento licencia</dt>
                <dd class="col-sm-9">{{ $conductor->fecha_vencimiento_licencia->format('d/m/Y') }}</dd>

                <dt class="col-sm-3">Estado</dt>
                <dd class="col-sm-9 text-capitalize">{{ str_replace('_', ' ', $conductor->estado) }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Envíos asignados</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Código</th><th>Fecha</th><th>Cliente</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @forelse ($conductor->envios as $envio)
                        <tr>
                            <td><a href="{{ route('envios.show', $envio) }}">{{ $envio->codigo }}</a></td>
                            <td>{{ $envio->fecha_envio->format('d/m/Y') }}</td>
                            <td>{{ $envio->cliente?->nombre }}</td>
                            <td class="text-capitalize">{{ str_replace('_', ' ', $envio->estado) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Sin envíos asignados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
