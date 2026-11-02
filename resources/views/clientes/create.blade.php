@extends('layouts.app')

@section('title', 'Nuevo cliente')

@section('content')
    <h1 class="h3 mb-4">Nuevo cliente</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('clientes._form', ['cliente' => null])
        </div>
    </div>
@endsection
