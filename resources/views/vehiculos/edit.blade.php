@extends('layouts.app')

@section('title', 'Editar vehículo')

@section('content')
    <h1 class="h3 mb-4">Editar vehículo</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('vehiculos._form', ['vehiculo' => $vehiculo])
        </div>
    </div>
@endsection
