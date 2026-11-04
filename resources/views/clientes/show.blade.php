@extends('layouts.app')

@section('title', 'Detalle del cliente')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Cliente: {{ $cliente->nombre }}</h1>
        <div class="d-flex gap-2">
            @if (! auth()->user()->esConsulta())
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
            @endif
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Documento</dt>
                <dd class="col-sm-9">{{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}</dd>

                <dt class="col-sm-3">Nombre / Razón social</dt>
                <dd class="col-sm-9">{{ $cliente->nombre }}</dd>

                <dt class="col-sm-3">Teléfono</dt>
                <dd class="col-sm-9">{{ $cliente->telefono }}</dd>

                <dt class="col-sm-3">Correo electrónico</dt>
                <dd class="col-sm-9">{{ $cliente->email ?? '—' }}</dd>

                <dt class="col-sm-3">Dirección</dt>
                <dd class="col-sm-9">{{ $cliente->direccion ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Envíos asociados</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Código</th><th>Fecha</th><th>Ruta</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @forelse ($cliente->envios as $envio)
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
