@php($conductor = $conductor ?? null)

<form method="POST" action="{{ $conductor ? route('conductores.update', $conductor) : route('conductores.store') }}">
    @csrf
    @if ($conductor)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Tipo de documento</label>
            <select name="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" required>
                @foreach (['CC', 'CE', 'PAS'] as $tipo)
                    <option value="{{ $tipo }}" @selected(old('tipo_documento', $conductor->tipo_documento ?? 'CC') === $tipo)>{{ $tipo }}</option>
                @endforeach
            </select>
            @error('tipo_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Número de documento</label>
            <input type="text" name="numero_documento" class="form-control @error('numero_documento') is-invalid @enderror"
                   value="{{ old('numero_documento', $conductor->numero_documento ?? '') }}" required>
            @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-5">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono', $conductor->telefono ?? '') }}" required>
            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Nombres</label>
            <input type="text" name="nombres" class="form-control @error('nombres') is-invalid @enderror"
                   value="{{ old('nombres', $conductor->nombres ?? '') }}" required>
            @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror"
                   value="{{ old('apellidos', $conductor->apellidos ?? '') }}" required>
            @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Número de licencia</label>
            <input type="text" name="numero_licencia" class="form-control @error('numero_licencia') is-invalid @enderror"
                   value="{{ old('numero_licencia', $conductor->numero_licencia ?? '') }}" required>
            @error('numero_licencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Categoría</label>
            <input type="text" name="categoria_licencia" class="form-control @error('categoria_licencia') is-invalid @enderror"
                   value="{{ old('categoria_licencia', $conductor->categoria_licencia ?? '') }}" placeholder="C2, C3..." required>
            @error('categoria_licencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Vence licencia</label>
            <input type="date" name="fecha_vencimiento_licencia"
                   class="form-control @error('fecha_vencimiento_licencia') is-invalid @enderror"
                   value="{{ old('fecha_vencimiento_licencia', isset($conductor) && $conductor->fecha_vencimiento_licencia ? $conductor->fecha_vencimiento_licencia->format('Y-m-d') : '') }}" required>
            @error('fecha_vencimiento_licencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-2">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                @foreach (['disponible' => 'Disponible', 'en_ruta' => 'En ruta', 'inactivo' => 'Inactivo'] as $valor => $texto)
                    <option value="{{ $valor }}" @selected(old('estado', $conductor->estado ?? 'disponible') === $valor)>{{ $texto }}</option>
                @endforeach
            </select>
            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $conductor ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('conductores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
