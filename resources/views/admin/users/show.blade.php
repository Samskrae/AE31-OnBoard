@extends('adminlte::page')

@section('title', 'Detalle de Usuario')

@section('content_header')
    <h1><i class="fas fa-user"></i> Detalle de Usuario: {{ $user->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">@lang('user.name'):</dt>
                <dd class="col-sm-9">{{ $user->name }}</dd>
                
                <dt class="col-sm-3">@lang('user.email'):</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                <dt class="col-sm-3">@lang('user.created_at'):</dt>
                <dd class="col-sm-9">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
            </dl>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>
    </div>
@stop