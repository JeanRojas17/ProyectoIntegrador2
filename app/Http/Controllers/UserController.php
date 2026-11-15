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

    public function destroy(Request $request, User $usuario): RedirectResponse
    {
        if ($usuario->is($request->user())) {
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