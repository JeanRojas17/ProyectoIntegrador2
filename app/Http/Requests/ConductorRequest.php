<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConductorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $conductor = $this->route('conductor');

        return [
            'tipo_documento' => ['required', Rule::in(['CC', 'CE', 'PAS'])],
            'numero_documento' => [
                'required', 'string', 'max:20',
                Rule::unique('conductores', 'numero_documento')->ignore($conductor),
            ],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'max:20'],
            'numero_licencia' => [
                'required', 'string', 'max:30',
                Rule::unique('conductores', 'numero_licencia')->ignore($conductor),
            ],
            'categoria_licencia' => ['required', 'string', 'max:10'],
            'fecha_vencimiento_licencia' => ['required', 'date'],
            'estado' => ['required', Rule::in(['disponible', 'en_ruta', 'inactivo'])],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El valor de :attribute ya está registrado.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo_documento' => 'tipo de documento',
            'numero_documento' => 'número de documento',
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'telefono' => 'teléfono',
            'numero_licencia' => 'número de licencia',
            'categoria_licencia' => 'categoría de licencia',
            'fecha_vencimiento_licencia' => 'fecha de vencimiento de licencia',
            'estado' => 'estado',
        ];
    }
}
