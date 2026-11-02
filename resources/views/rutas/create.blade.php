@extends('layouts.app')

@section('title', 'Nueva ruta')

@section('content')
    <h1 class="h3 mb-4">Nueva ruta</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('rutas._form', ['ruta' => null])
        </div>
    </div>
@endsection
