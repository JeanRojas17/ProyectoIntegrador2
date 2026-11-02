@php($envio = $envio ?? null)

<form method="POST" action="{{ $envio ? route('envios.update', $envio) : route('envios.store') }}">
    @csrf
    @if ($envio)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                   value="{{ old('codigo', $envio->codigo ?? 'ENV-' . strtoupper(uniqid())) }}" required>
            @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Fecha de envío</label>
            <input type="date" name="fecha_envio" class="form-control @error('fecha_envio') is-invalid @enderror"
                   value="{{ old('fecha_envio', isset($envio) && $envio->fecha_envio ? $envio->fecha_envio->format('Y-m-d') : date('Y-m-d')) }}" required>
            @error('fecha_envio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                @foreach (['pendiente' => 'Pendiente', 'en_transito' => 'En tránsito', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado'] as $valor => $texto)
                    <option value="{{ $valor }}" @selected(old('estado', $envio->estado ?? 'pendiente') === $valor)>{{ $texto }}</option>
                @endforeach
            </select>
            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Cliente</label>
            <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" @selected((int) old('cliente_id', $envio->cliente_id ?? 0) === $cliente->id)>
                        {{ $cliente->nombre }}
                    </option>
                @endforeach
            </select>
            @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Conductor</label>
            <select name="conductor_id" class="form-select @error('conductor_id') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                @foreach ($conductores as $conductor)
                    <option value="{{ $conductor->id }}" @selected((int) old('conductor_id', $envio->conductor_id ?? 0) === $conductor->id)>
                        {{ $conductor->nombres }} {{ $conductor->apellidos }}
                    </option>
                @endforeach
            </select>
            @error('conductor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Vehículo</label>
            <select name="vehiculo_id" class="form-select @error('vehiculo_id') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                @foreach ($vehiculos as $vehiculo)
                    <option value="{{ $vehiculo->id }}" @selected((int) old('vehiculo_id', $envio->vehiculo_id ?? 0) === $vehiculo->id)>
                        {{ $vehiculo->placa }} — {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                    </option>
                @endforeach
            </select>
            @error('vehiculo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Ruta</label>
            <select name="ruta_id" class="form-select @error('ruta_id') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                @foreach ($rutas as $ruta)
                    <option value="{{ $ruta->id }}" @selected((int) old('ruta_id', $envio->ruta_id ?? 0) === $ruta->id)>
                        {{ $ruta->origen }} → {{ $ruta->destino }}
                    </option>
                @endforeach
            </select>
            @error('ruta_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label">Descripción de la carga</label>
            <textarea name="descripcion_carga" rows="3" class="form-control @error('descripcion_carga') is-invalid @enderror" required>{{ old('descripcion_carga', $envio->descripcion_carga ?? '') }}</textarea>
            @error('descripcion_carga')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Peso (kg)</label>
            <input type="number" step="0.01" name="peso_kg" class="form-control @error('peso_kg') is-invalid @enderror"
                   value="{{ old('peso_kg', $envio->peso_kg ?? '') }}" required>
            @error('peso_kg')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Valor declarado</label>
            <input type="number" step="0.01" name="valor_declarado" class="form-control @error('valor_declarado') is-invalid @enderror"
                   value="{{ old('valor_declarado', $envio->valor_declarado ?? '') }}">
            @error('valor_declarado')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $envio ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
