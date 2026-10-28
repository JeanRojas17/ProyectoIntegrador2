<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoriaId = $this->categoria ? $this->categoria->id : $this->route('categoria');
        return [
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoriaId,
            'descripcion' => 'nullable|string',
            'status' => 'boolean',
        ];
    }
}