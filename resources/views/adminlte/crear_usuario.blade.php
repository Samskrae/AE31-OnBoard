@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1>Crear usuario</h1>
@stop

@section('content')
    <form action="{{ route('adminlte.usuario.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nombre</label>
            <input name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <button class="btn btn-primary">Crear usuario</button>
    </form>
@stop
