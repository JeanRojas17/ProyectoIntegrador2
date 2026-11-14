# Módulo de Usuarios: credenciales individuales por rol

> **Importante:** este documento es una guía para que otro compañero implemente el cambio.
> No se ha modificado ningún archivo del proyecto todavía.
>
> Objetivo: que cada conductor, cliente y auxiliar tenga su **propio usuario y contraseña**
> (no una cuenta compartida `conductor@...`). El administrador es quien crea esas cuentas
> y las vincula al conductor o cliente correspondiente.

---

## 1. Situación actual

- `users` ya tiene la columna `rol` (`administrador`, `conductor`, `cliente`, `auxiliar`, `consulta`).
- `conductores.user_id` y `clientes.user_id` ya existen, son `nullable` y `unique`, con `nullOnDelete`.
- El filtrado ya funciona en `EnvioController`, `RutaController` y `MiMapaRBLController`
  usando `$user->conductor` y `$user->cliente`. **No hay que tocar esos controladores.**
- Solo existe una cuenta de conductor compartida: `conductor@transportesrbl.com`.
- `app/Http/Controllers/UserController.php` existe pero está **roto** (es una copia de
  `ClienteController`, sin rutas ni vistas). Hay que reemplazarlo completo.
- No hay rutas ni vistas para administrar usuarios.

---

## 2. Archivos que se crean o modifican

| Acción | Archivo |
|--------|---------|
| Modificar | `routes/web.php` |
| Reemplazar completo | `app/Http/Controllers/UserController.php` |
| Crear | `app/Http/Requests/UserRequest.php` |
| Crear | `resources/views/usuarios/index.blade.php` |
| Crear | `resources/views/usuarios/create.blade.php` |
| Crear | `resources/views/usuarios/edit.blade.php` |
| Crear | `resources/views/usuarios/_form.blade.php` |
| Modificar | `resources/views/layouts/app.blade.php` (menú lateral) |
| Opcional | `tests/Feature/UserManagementTest.php` |

No se requiere migración nueva.

---

## 3. Rutas

En `routes/web.php`, agregar el import arriba:

```php
use App\Http\Controllers\UserController;
```

Dentro del grupo que ya existe para el administrador:

```php
Route::middleware('role:administrador')->group(function () {
    // ... rutas existentes de clientes, conductores, etc.

    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});
```

Así un usuario con rol `consulta`, `conductor`, `cliente` o `auxiliar` no puede entrar al módulo.

---

## 4. Form Request

Crear `app/Http/Requests/UserRequest.php`:

```php
<?php

namespace App\Http\Requests;

use App\Models\Cliente;
use App\Models\Conductor;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario?->id),
            ],
            'rol' => [
                'required',
                Rule::in([
                    User::ROL_ADMINISTRADOR,
                    User::ROL_CONDUCTOR,
                    User::ROL_CLIENTE,
                    User::ROL_AUXILIAR,
                    User::ROL_CONSULTA,
                ]),
            ],
            'password' => [
                $usuario ? 'nullable' : 'required',
                'confirmed',
                Password::min(8),
            ],
            'conductor_id' => [
                'exclude_unless:rol,' . User::ROL_CONDUCTOR,
                'nullable',
                'required_if:rol,' . User::ROL_CONDUCTOR,
                'integer',
                'exists:conductores,id',
                function (string $attribute, mixed $value, Closure $fail) use ($usuario) {
                    if (! $value) {
                        return;
                    }

                    $conductor = Conductor::find($value);

                    if ($conductor && $conductor->user_id && $conductor->user_id !== $usuario?->id) {
                        $fail('El conductor seleccionado ya tiene una cuenta de acceso.');
                    }
                },
            ],
            'cliente_id' => [
                'exclude_unless:rol,' . User::ROL_CLIENTE,
                'nullable',
                'required_if:rol,' . User::ROL_CLIENTE,
                'integer',
                'exists:clientes,id',
                function (string $attribute, mixed $value, Closure $fail) use ($usuario) {
                    if (! $value) {
                        return;
                    }

                    $cliente = Cliente::find($value);

                    if ($cliente && $cliente->user_id && $cliente->user_id !== $usuario?->id) {
                        $fail('El cliente seleccionado ya tiene una cuenta de acceso.');
                    }
                },
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'rol' => 'rol',
            'password' => 'contraseña',
            'conductor_id' => 'conductor',
            'cliente_id' => 'cliente',
        ];
    }
}
```

---

## 5. Controlador

Reemplazar **todo** `app/Http/Controllers/UserController.php` por:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Cliente;
use App\Models\Conductor;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = $request->input('buscar');

        $usuarios = User::with(['conductor', 'cliente'])
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")
                      ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create(): View
    {
        return view('usuarios.create', [
            'usuario' => null,
            'conductores' => $this->conductoresDisponibles(),
            'clientes' => $this->clientesDisponibles(),
            'roles' => $this->roles(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            $usuario = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'rol' => $data['rol'],
            ]);

            $this->vincular($usuario, $data);
        });

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario): View
    {
        return view('usuarios.edit', [
            'usuario' => $usuario,
            'conductores' => $this->conductoresDisponibles($usuario),
            'clientes' => $this->clientesDisponibles($usuario),
            'roles' => $this->roles(),
        ]);
    }

    public function update(UserRequest $request, User $usuario): RedirectResponse
    {
        DB::transaction(function () use ($request, $usuario) {
            $data = $request->validated();

            $usuario->name = $data['name'];
            $usuario->email = $data['email'];
            $usuario->rol = $data['rol'];

            if (! empty($data['password'])) {
                $usuario->password = $data['password'];
            }

            $usuario->save();

            $this->desvincular($usuario);
            $this->vincular($usuario, $data);
        });

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->is(auth()->user())) {
            return back()->withErrors([
                'usuario' => 'No puedes eliminar tu propio usuario.',
            ]);
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    private function vincular(User $usuario, array $data): void
    {
        if ($data['rol'] === User::ROL_CONDUCTOR && ! empty($data['conductor_id'])) {
            Conductor::whereKey($data['conductor_id'])->update(['user_id' => $usuario->id]);
        }

        if ($data['rol'] === User::ROL_CLIENTE && ! empty($data['cliente_id'])) {
            Cliente::whereKey($data['cliente_id'])->update(['user_id' => $usuario->id]);
        }
    }

    private function desvincular(User $usuario): void
    {
        Conductor::where('user_id', $usuario->id)->update(['user_id' => null]);
        Cliente::where('user_id', $usuario->id)->update(['user_id' => null]);
    }

    private function conductoresDisponibles(?User $usuario = null): Collection
    {
        return Conductor::where(function ($query) use ($usuario) {
                $query->whereNull('user_id');

                if ($usuario) {
                    $query->orWhere('user_id', $usuario->id);
                }
            })
            ->orderBy('nombres')
            ->get();
    }

    private function clientesDisponibles(?User $usuario = null): Collection
    {
        return Cliente::where(function ($query) use ($usuario) {
                $query->whereNull('user_id');

                if ($usuario) {
                    $query->orWhere('user_id', $usuario->id);
                }
            })
            ->orderBy('nombre')
            ->get();
    }

    private function roles(): array
    {
        return [
            User::ROL_ADMINISTRADOR => 'Administrador',
            User::ROL_CONDUCTOR => 'Conductor',
            User::ROL_CLIENTE => 'Cliente',
            User::ROL_AUXILIAR => 'Auxiliar',
            User::ROL_CONSULTA => 'Consulta',
        ];
    }
}
```

> La contraseña se guarda hasheada automáticamente porque el modelo `User` ya tiene
> `'password' => 'hashed'` en `casts()`. No hay que usar `Hash::make` manualmente.

---

## 6. Vista de listado

Crear `resources/views/usuarios/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo usuario
        </a>
    </div>

    <form method="GET" action="{{ route('usuarios.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="buscar" class="form-control"
                       placeholder="Buscar por nombre o correo..."
                       value="{{ request('buscar') }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Vínculo</th>
                        <th class="table-actions text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td><span class="badge bg-secondary text-capitalize">{{ $usuario->rol }}</span></td>
                            <td>
                                @if ($usuario->conductor)
                                    Conductor: {{ $usuario->conductor->nombres }} {{ $usuario->conductor->apellidos }}
                                @elseif ($usuario->cliente)
                                    Cliente: {{ $usuario->cliente->nombre }}
                                @else
                                    <span class="text-muted">Sin vínculo</span>
                                @endif
                            </td>
                            <td class="table-actions text-end">
                                <a href="{{ route('usuarios.edit', $usuario) }}"
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if (! $usuario->is(auth()->user()))
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('¿Eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $usuarios->links() }}</div>
@endsection
```

---

## 7. Formulario compartido

Crear `resources/views/usuarios/_form.blade.php`:

```blade
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
```

---

## 8. Vistas de crear y editar

Crear `resources/views/usuarios/create.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nuevo usuario</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @include('usuarios._form')
        </div>
    </div>
@endsection
```

Crear `resources/views/usuarios/edit.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Editar usuario</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @include('usuarios._form')
        </div>
    </div>
@endsection
```

---

## 9. Menú lateral

En `resources/views/layouts/app.blade.php`, dentro del `<ul class="nav nav-pills ...">`,
agregar este bloque (solo visible para administrador). Puede ir después del enlace de
Reportes o de Conductores:

```blade
@if ($usuario->esAdministrador())
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
           href="{{ route('usuarios.index') }}">
            <i class="bi bi-person-gear"></i> Usuarios
        </a>
    </li>
@endif
```

---

## 10. Cómo queda el flujo

1. El administrador entra a **Usuarios → Nuevo usuario**.
2. Elige el rol:
   - `conductor`: debe seleccionar el conductor al que pertenece la cuenta.
   - `cliente`: debe seleccionar el cliente.
   - `auxiliar` / `consulta` / `administrador`: no se vincula a nada.
3. Escribe el correo y la contraseña.
4. Al guardar:
   - Se crea el registro en `users` con el rol elegido.
   - Si es conductor, se llena `conductores.user_id`.
   - Si es cliente, se llena `clientes.user_id`.
5. Esa persona inicia sesión con **su propio correo y contraseña**.
6. El sistema ya muestra automáticamente solo lo que le corresponde:
   - Conductor: sus envíos, sus rutas y su mapa.
   - Cliente: solo sus envíos.
   - Auxiliar: solo el panel.
   - Consulta: lectura general y reportes.

No hay que modificar `EnvioController`, `RutaController` ni `MiMapaRBLController`, porque
ya filtran por `$user->conductor` y `$user->cliente`.

---

## 11. Detalles importantes a respetar

- **Email único**: no se puede repetir en `users`.
- **Un conductor/cliente = una cuenta**: la validación impide vincular dos usuarios al mismo conductor o cliente.
- **Contraseña opcional al editar**: si se deja vacía, se conserva la actual.
- **No autoeliminarse**: el administrador no puede borrar su propio usuario.
- **Al eliminar un usuario**: `conductores.user_id` y `clientes.user_id` quedan en `null` por `nullOnDelete`.
- **Al cambiar el rol**: se desvincula el conductor/cliente anterior y se vincula el nuevo si aplica.
- **La cuenta compartida `conductor@transportesrbl.com`**: se puede dejar como demo o eliminarla
  desde Usuarios. Si se elimina, el conductor 1 queda libre para asignarle una cuenta propia.
- **No crear un módulo de "auxiliares" aparte**: los auxiliares son usuarios con rol `auxiliar`.

---

## 12. Pruebas manuales

1. Iniciar sesión como `admin@transportesrbl.com` / `AdminRBL2026*`.
2. Crear un usuario con rol `conductor`, seleccionando un conductor sin cuenta.
3. Cerrar sesión e iniciar con el correo y contraseña de ese conductor.
4. Verificar que solo aparezcan sus envíos, sus rutas y su mapa.
5. Repetir con un `cliente` y comprobar que solo vea sus envíos.
6. Crear un `auxiliar` y verificar que solo vea el panel.
7. Entrar con `consulta@transportesrbl.com` e intentar abrir `/usuarios`: debe dar 403.

---

## 13. Prueba automática opcional

Crear `tests/Feature/UserManagementTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\Conductor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_crea_usuario_conductor_vinculado(): void
    {
        $admin = User::factory()->create(['rol' => 'administrador']);

        $conductor = Conductor::create([
            'tipo_documento' => 'CC',
            'numero_documento' => '1000000001',
            'nombres' => 'Conductor',
            'apellidos' => 'Prueba',
            'telefono' => '3000000001',
            'numero_licencia' => 'LIC-TEST-01',
            'categoria_licencia' => 'C3',
            'fecha_vencimiento_licencia' => '2028-01-01',
            'estado' => 'disponible',
        ]);

        $this->actingAs($admin)->post(route('usuarios.store'), [
            'name' => 'Conductor Uno',
            'email' => 'conductor1@test.com',
            'rol' => 'conductor',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'conductor_id' => $conductor->id,
        ])->assertRedirect(route('usuarios.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'conductor1@test.com',
            'rol' => 'conductor',
        ]);

        $this->assertNotNull($conductor->fresh()->user_id);
    }

    public function test_no_administrador_no_puede_entrar_a_usuarios(): void
    {
        $consulta = User::factory()->create(['rol' => 'consulta']);

        $this->actingAs($consulta)->get(route('usuarios.index'))->assertForbidden();
    }
}
```

---

## 14. Comandos para verificar

```bash
php artisan view:clear
php artisan view:cache
php artisan test
```

Si se usa `php artisan test`, el único fallo esperado es `ExampleTest` por el redirect 302
de `/` (ya existía antes de este cambio).

---

## 15. Commit sugerido

```bash
git add app/Http/Controllers/UserController.php \
        app/Http/Requests/UserRequest.php \
        routes/web.php \
        resources/views/layouts/app.blade.php \
        resources/views/usuarios \
        tests/Feature/UserManagementTest.php

git commit -m "Agrega modulo de usuarios con credenciales por rol y vinculo a conductores y clientes"
```
