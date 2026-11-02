@php($ruta = $ruta ?? null)

<form method="POST" action="{{ $ruta ? route('rutas.update', $ruta) : route('rutas.store') }}">
    @csrf
    @if ($ruta)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Origen</label>
            <input type="text" name="origen" class="form-control @error('origen') is-invalid @enderror"
                   value="{{ old('origen', $ruta->origen ?? '') }}" required>
            @error('origen')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Destino</label>
            <input type="text" name="destino" class="form-control @error('destino') is-invalid @enderror"
                   value="{{ old('destino', $ruta->destino ?? '') }}" required>
            @error('destino')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Distancia (km)</label>
            <input type="number" step="0.01" name="distancia_km" class="form-control @error('distancia_km') is-invalid @enderror"
                   value="{{ old('distancia_km', $ruta->distancia_km ?? '') }}" required>
            @error('distancia_km')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tiempo estimado (horas)</label>
            <input type="number" step="0.01" name="tiempo_estimado_horas"
                   class="form-control @error('tiempo_estimado_horas') is-invalid @enderror"
                   value="{{ old('tiempo_estimado_horas', $ruta->tiempo_estimado_horas ?? '') }}">
            @error('tiempo_estimado_horas')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tarifa base</label>
            <input type="number" step="0.01" name="tarifa_base" class="form-control @error('tarifa_base') is-invalid @enderror"
                   value="{{ old('tarifa_base', $ruta->tarifa_base ?? '') }}" required>
            @error('tarifa_base')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $ruta ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('rutas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
