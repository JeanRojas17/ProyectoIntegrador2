@extends('layouts.app')

@section('title', 'Editar envío')

@section('content')
    <h1 class="h3 mb-4">Editar envío</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('envios._form', ['envio' => $envio])
        </div>
    </div>
@endsection
