@extends('layouts.app')

@section('title', 'Detalle del envío')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Envío: {{ $envio->codigo }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('envios.edit', $envio) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar</a>
            <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Datos del envío</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Código</dt>
                        <dd class="col-sm-8">{{ $envio->codigo }}</dd>

                        <dt class="col-sm-4">Fecha</dt>
                        <dd class="col-sm-8">{{ $envio->fecha_envio->format('d/m/Y') }}</dd>

                        <dt class="col-sm-4">Estado</dt>
                        <dd class="col-sm-8 text-capitalize">{{ str_replace('_', ' ', $envio->estado) }}</dd>

                        <dt class="col-sm-4">Peso</dt>
                        <dd class="col-sm-8">{{ number_format($envio->peso_kg, 2) }} kg</dd>

                        <dt class="col-sm-4">Valor declarado</dt>
                        <dd class="col-sm-8">{{ $envio->valor_declarado ? '$' . number_format($envio->valor_declarado, 2) : '—' }}</dd>

                        <dt class="col-sm-4">Descripción</dt>
                        <dd class="col-sm-8">{{ $envio->descripcion_carga }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Participantes</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Cliente</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('clientes.show', $envio->cliente) }}">{{ $envio->cliente?->nombre }}</a>
                        </dd>

                        <dt class="col-sm-4">Conductor</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('conductores.show', $envio->conductor) }}">
                                {{ $envio->conductor?->nombres }} {{ $envio->conductor?->apellidos }}
                            </a>
                        </dd>

                        <dt class="col-sm-4">Vehículo</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('vehiculos.show', $envio->vehiculo) }}">{{ $envio->vehiculo?->placa }}</a>
                        </dd>

                        <dt class="col-sm-4">Ruta</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('rutas.show', $envio->ruta) }}">
                                {{ $envio->ruta?->origen }} → {{ $envio->ruta?->destino }}
                            </a>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
