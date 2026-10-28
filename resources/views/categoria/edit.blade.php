@extends('layouts.app')

@section('content')
<div class=\"container\">
    <h1>Editar Categoría</h1>

    <form action=\"{{ route('categoria.update', ) }}\" method=\"POST\">
        @csrf
        @method('PUT')

        <div class=\"mb-3\">
            <label>Nombre</label>
            <input type=\"text\" name=\"nombre\" class=\"form-control\" value=\"{{ ->nombre }}\" required>
        </div>

        <div class=\"mb-3\">
            <label>Descripción</label>
            <textarea name=\"descripcion\" class=\"form-control\">{{ ->descripcion }}</textarea>
        </div>

        <div class=\"mb-3\">
            <label>Status</label>
            <select name=\"status\" class=\"form-control\">
                <option value=\"1\" {{ ->status ? 'selected' : '' }}>Activo</option>
                <option value=\"0\" {{ !->status ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button class=\"btn btn-primary\">Actualizar</button>
        <a href=\"{{ route('categoria.index') }}\" class=\"btn btn-secondary\">Cancelar</a>
    </form>
</div>
@endsection
