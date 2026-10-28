@extends('layouts.app')

@section('content')
<div class=\"container\">
    <div class=\"d-flex justify-content-between mb-3\">
        <h1>Categorías</h1>
        <a href=\"{{ route('categoria.create') }}\" class=\"btn btn-primary\">Nueva Categoría</a>
    </div>

    @if(session('success'))
        <div class=\"alert alert-success\">{{ session('success') }}</div>
    @endif

    <table class=\"table table-bordered\">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Status</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach( as )
                <tr>
                    <td>{{ ->id }}</td>
                    <td>{{ ->nombre }}</td>
                    <td>{{ ->descripcion }}</td>
                    <td>{{ ->status ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <a href=\"{{ route('categoria.edit', ) }}\" class=\"btn btn-sm btn-warning\">Editar</a>
                        <form action=\"{{ route('categoria.destroy', ) }}\" method=\"POST\" style=\"display:inline\">
                            @csrf
                            @method('DELETE')
                            <button class=\"btn btn-sm btn-danger\" onclick=\"return confirm('¿Eliminar categoría?')\">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ ->links() }}
</div>
@endsection
