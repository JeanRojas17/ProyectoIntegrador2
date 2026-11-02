@extends('layouts.app')

@section('title', 'Editar conductor')

@section('content')
    <h1 class="h3 mb-4">Editar conductor</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('conductores._form', ['conductor' => $conductor])
        </div>
    </div>
@endsection
