@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Editar usuario</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @include('usuarios._form')
        </div>
    </div>
@endsection