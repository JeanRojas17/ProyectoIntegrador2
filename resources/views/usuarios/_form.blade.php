@php($esEdicion = isset($usuario) && $usuario)
@php($rolActual = old('rol', $usuario->rol ?? \App\Models\User::ROL_CONDUCTOR))

<form method="POST" action="{{ $esEdicion ? route('usuarios.update', $usuario) : route('usuarios.store') }}">
    @csrf
    @if ($esEdicion)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $usuario?->name ?? '') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Correo electrónico (será el usuario de acceso)</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $usuario?->email ?? '') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Rol</label>
            <select name="rol" id="rol" class="form-select @error('rol') is-invalid @enderror" required>
                @foreach ($roles as $valor => $texto)
                    <option value="{{ $valor }}" @selected($rolActual === $valor)>{{ $texto }}</option>
                @endforeach
            </select>
            @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4" id="campoConductor">
            <label class="form-label">Conductor vinculado</label>
            <select name="conductor_id" class="form-select @error('conductor_id') is-invalid @enderror">
                <option value="">Selecciona un conductor...</option>
                @foreach ($conductores as $conductor)
                    <option value="{{ $conductor->id }}"
                        @selected((string) old('conductor_id', $usuario?->conductor?->id) === (string) $conductor->id)>
                        {{ $conductor->nombres }} {{ $conductor->apellidos }} ({{ $conductor->numero_documento }})
                    </option>
                @endforeach
            </select>
            @error('conductor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4" id="campoCliente">
            <label class="form-label">Cliente vinculado</label>
            <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                <option value="">Selecciona un cliente...</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}"
                        @selected((string) old('cliente_id', $usuario?->cliente?->id) === (string) $cliente->id)>
                        {{ $cliente->nombre }} ({{ $cliente->numero_documento }})
                    </option>
                @endforeach
            </select>
            @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">
                Contraseña {{ $esEdicion ? '(dejar vacío para conservarla)' : '' }}
            </label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   {{ $esEdicion ? '' : 'required' }}>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control"
                   {{ $esEdicion ? '' : 'required' }}>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $esEdicion ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<script>
    function actualizarCamposRol() {
        var rol = document.getElementById('rol').value;
        var campoConductor = document.getElementById('campoConductor');
        var campoCliente = document.getElementById('campoCliente');
        var selectConductor = document.querySelector('[name="conductor_id"]');
        var selectCliente = document.querySelector('[name="cliente_id"]');

        campoConductor.classList.toggle('d-none', rol !== 'conductor');
        campoCliente.classList.toggle('d-none', rol !== 'cliente');

        if (rol !== 'conductor') {
            selectConductor.value = '';
        }

        if (rol !== 'cliente') {
            selectCliente.value = '';
        }
    }

    document.getElementById('rol').addEventListener('change', actualizarCamposRol);
    actualizarCamposRol();
</script>