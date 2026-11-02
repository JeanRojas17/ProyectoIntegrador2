@extends('layouts.app')

@section('title', 'Nuevo envío')

@section('content')
    <h1 class="h3 mb-4">Nuevo envío</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('envios._form', ['envio' => null])
        </div>
    </div>
@endsection
