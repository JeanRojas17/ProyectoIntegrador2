@extends('layouts.app')

@section('title', 'Editar ruta')

@section('content')
    <h1 class="h3 mb-4">Editar ruta</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('rutas._form', ['ruta' => $ruta])
        </div>
    </div>
@endsection
