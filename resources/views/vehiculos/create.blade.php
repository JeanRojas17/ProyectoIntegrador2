@extends('layouts.app')

@section('title', 'Nuevo vehículo')

@section('content')
    <h1 class="h3 mb-4">Nuevo vehículo</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('vehiculos._form', ['vehiculo' => null])
        </div>
    </div>
@endsection
