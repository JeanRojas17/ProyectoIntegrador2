<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RutaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origen' => ['required', 'string', 'max:100'],
            'destino' => ['required', 'string', 'max:100', 'different:origen'],
            'distancia_km' => ['required', 'numeric', 'min:0.01'],
            'tiempo_estimado_horas' => ['nullable', 'numeric', 'min:0.01'],
            'tarifa_base' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'different' => 'El destino debe ser diferente al origen.',
        ];
    }

    public function attributes(): array
    {
        return [
            'origen' => 'origen',
            'destino' => 'destino',
            'distancia_km' => 'distancia (km)',
            'tiempo_estimado_horas' => 'tiempo estimado (horas)',
            'tarifa_base' => 'tarifa base',
        ];
    }
}
