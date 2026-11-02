@php($vehiculo = $vehiculo ?? null)

<form method="POST" action="{{ $vehiculo ? route('vehiculos.update', $vehiculo) : route('vehiculos.store') }}">
    @csrf
    @if ($vehiculo)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Placa</label>
            <input type="text" name="placa" class="form-control @error('placa') is-invalid @enderror"
                   value="{{ old('placa', $vehiculo->placa ?? '') }}" required>
            @error('placa')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror"
                   value="{{ old('marca', $vehiculo->marca ?? '') }}" required>
            @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror"
                   value="{{ old('modelo', $vehiculo->modelo ?? '') }}" required>
            @error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Año</label>
            <input type="number" name="anio" class="form-control @error('anio') is-invalid @enderror"
                   value="{{ old('anio', $vehiculo->anio ?? date('Y')) }}" min="1950" max="{{ date('Y') + 1 }}" required>
            @error('anio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tipo de vehículo</label>
            <input type="text" name="tipo_vehiculo" class="form-control @error('tipo_vehiculo') is-invalid @enderror"
                   value="{{ old('tipo_vehiculo', $vehiculo->tipo_vehiculo ?? '') }}" placeholder="Camión, furgón..." required>
            @error('tipo_vehiculo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Capacidad de carga (kg)</label>
            <input type="number" step="0.01" name="capacidad_carga" class="form-control @error('capacidad_carga') is-invalid @enderror"
                   value="{{ old('capacidad_carga', $vehiculo->capacidad_carga ?? '') }}" required>
            @error('capacidad_carga')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                @foreach (['disponible' => 'Disponible', 'en_ruta' => 'En ruta', 'mantenimiento' => 'Mantenimiento', 'inactivo' => 'Inactivo'] as $valor => $texto)
                    <option value="{{ $valor }}" @selected(old('estado', $vehiculo->estado ?? 'disponible') === $valor)>{{ $texto }}</option>
                @endforeach
            </select>
            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $vehiculo ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
