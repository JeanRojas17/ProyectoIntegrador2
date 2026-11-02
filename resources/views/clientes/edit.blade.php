@extends('layouts.app')

@section('title', 'Editar cliente')

@section('content')
    <h1 class="h3 mb-4">Editar cliente</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('clientes._form', ['cliente' => $cliente])
        </div>
    </div>
@endsection
