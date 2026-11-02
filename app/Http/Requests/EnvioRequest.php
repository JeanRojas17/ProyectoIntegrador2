<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnvioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => [
                'required', 'string', 'max:20',
                Rule::unique('envios', 'codigo')->ignore($this->route('envio')),
            ],
            'cliente_id' => ['required', 'exists:clientes,id'],
            'conductor_id' => ['required', 'exists:conductores,id'],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'ruta_id' => ['required', 'exists:rutas,id'],
            'fecha_envio' => ['required', 'date'],
            'descripcion_carga' => ['required', 'string', 'max:1000'],
            'peso_kg' => ['required', 'numeric', 'min:0.01'],
            'valor_declarado' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['required', Rule::in(['pendiente', 'en_transito', 'entregado', 'cancelado'])],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El valor de :attribute ya está registrado.',
            'exists' => 'El valor seleccionado en :attribute no es válido.',
            'numeric' => 'El campo :attribute debe ser numérico.',
        ];
    }

    public function attributes(): array
    {
        return [
            'codigo' => 'código',
            'cliente_id' => 'cliente',
            'conductor_id' => 'conductor',
            'vehiculo_id' => 'vehículo',
            'ruta_id' => 'ruta',
            'fecha_envio' => 'fecha de envío',
            'descripcion_carga' => 'descripción de la carga',
            'peso_kg' => 'peso (kg)',
            'valor_declarado' => 'valor declarado',
            'estado' => 'estado',
        ];
    }
}
