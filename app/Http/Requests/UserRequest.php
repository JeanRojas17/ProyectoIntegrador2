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
