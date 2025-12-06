@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1><i class="fas fa-user-plus"></i> @lang('user.create_user')</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- Formulario de Creación (POST) --}}
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                {{-- Incluimos el parcial con los campos --}}
                @include('admin.users._form') 
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> @lang('general.save')
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times-circle"></i> @lang('general.cancel')
                </a>
            </form>
        </div>
    </div>
@stop