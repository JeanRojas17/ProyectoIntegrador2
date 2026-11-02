@php($cliente = $cliente ?? null)

<form method="POST" action="{{ $cliente ? route('clientes.update', $cliente) : route('clientes.store') }}">
    @csrf
    @if ($cliente)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Tipo de documento</label>
            <select name="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" required>
                @foreach (['CC', 'CE', 'NIT', 'PAS'] as $tipo)
                    <option value="{{ $tipo }}" @selected(old('tipo_documento', $cliente->tipo_documento ?? 'CC') === $tipo)>{{ $tipo }}</option>
                @endforeach
            </select>
            @error('tipo_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Número de documento</label>
            <input type="text" name="numero_documento" class="form-control @error('numero_documento') is-invalid @enderror"
                   value="{{ old('numero_documento', $cliente->numero_documento ?? '') }}" required>
            @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Nombre / Razón social</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $cliente->nombre ?? '') }}" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono', $cliente->telefono ?? '') }}" required>
            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $cliente->email ?? '') }}">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                   value="{{ old('direccion', $cliente->direccion ?? '') }}">
            @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $cliente ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
