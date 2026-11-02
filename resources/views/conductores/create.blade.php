@extends('layouts.app')

@section('title', 'Nuevo conductor')

@section('content')
    <h1 class="h3 mb-4">Nuevo conductor</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @include('conductores._form', ['conductor' => null])
        </div>
    </div>
@endsection
