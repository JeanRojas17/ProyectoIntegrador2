<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'placa' => [
                'required', 'string', 'max:10',
                Rule::unique('vehiculos', 'placa')->ignore($this->route('vehiculo')),
            ],
            'marca' => ['required', 'string', 'max:50'],
            'modelo' => ['required', 'string', 'max:50'],
            'anio' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'tipo_vehiculo' => ['required', 'string', 'max:50'],
            'capacidad_carga' => ['required', 'numeric', 'min:0.01'],
            'estado' => ['required', Rule::in(['disponible', 'en_ruta', 'mantenimiento', 'inactivo'])],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El valor de :attribute ya está registrado.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'integer' => 'El campo :attribute debe ser un número entero.',
        ];
    }

    public function attributes(): array
    {
        return [
            'placa' => 'placa',
            'marca' => 'marca',
            'modelo' => 'modelo',
            'anio' => 'año',
            'tipo_vehiculo' => 'tipo de vehículo',
            'capacidad_carga' => 'capacidad de carga',
            'estado' => 'estado',
        ];
    }
}
