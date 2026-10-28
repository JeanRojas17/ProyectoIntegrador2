@extends('layouts.app')

@section('content')
<div class=\"container\">
    <h1>Crear Categoría</h1>

    <form action=\"{{ route('categoria.store') }}\" method=\"POST\">
        @csrf

        <div class=\"mb-3\">
            <label>Nombre</label>
            <input type=\"text\" name=\"nombre\" class=\"form-control\" required>
        </div>

        <div class=\"mb-3\">
            <label>Descripción</label>
            <textarea name=\"descripcion\" class=\"form-control\"></textarea>
        </div>

        <div class=\"mb-3\">
            <label>Status</label>
            <select name=\"status\" class=\"form-control\">
                <option value=\"1\">Activo</option>
                <option value=\"0\">Inactivo</option>
            </select>
        </div>

        <button class=\"btn btn-primary\">Guardar</button>
        <a href=\"{{ route('categoria.index') }}\" class=\"btn btn-secondary\">Cancelar</a>
    </form>
</div>
@endsection
