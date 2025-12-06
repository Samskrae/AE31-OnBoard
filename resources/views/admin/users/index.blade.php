@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <h1><i class="fas fa-users"></i> @lang('user.user_management')</h1>
@stop

@section('content')
    {{-- Muestra mensajes de sesión (success o error) --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('user.user_list')</h3>
            <div class="card-tools">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> @lang('user.new_user')
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>@lang('user.name')</th>
                        <th>@lang('user.email')</th>
                        <th>@lang('user.created_at')</th>
                        <th style="width: 150px">@lang('general.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                {{-- Enlace de Edición --}}
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-xs btn-info" title="@lang('general.edit')">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                {{-- Botón de Eliminación (Formulario DELETE) --}}
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="@lang('general.delete')" 
                                            onclick="return confirm('@lang('user.confirm_delete', ['name' => $user->name])')"
                                            {{-- Deshabilitar botón si es el usuario autenticado --}}
                                            @if (auth()->id() == $user->id) disabled @endif>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Enlaces de paginación (si se implementó en el controlador) --}}
        @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="card-footer clearfix">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@stop