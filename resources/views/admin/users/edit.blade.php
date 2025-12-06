@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1><i class="fas fa-user-edit"></i> @lang('user.edit_user', ['name' => $user->name])</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                @include('admin.users._form', ['user' => $user]) 
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> @lang('general.update')
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times-circle"></i> @lang('general.cancel')
                </a>
            </form>
        </div>
    </div>
@stop