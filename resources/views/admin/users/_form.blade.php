{{-- Campo Nombre --}}
<div class="form-group">
    <label for="name">@lang('user.name')</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
           value="{{ old('name', $user->name ?? '') }}" required>
    @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>

{{-- Campo Email --}}
<div class="form-group">
    <label for="email">@lang('user.email')</label>
    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
           value="{{ old('email', $user->email ?? '') }}" required>
    @error('email')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>

{{-- Campo Contraseña --}}
<div class="form-group">
    <label for="password">@lang('user.password')</label>
    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
           placeholder="{{ isset($user) ? __('user.password_placeholder_edit') : '' }}"
           {{-- Si la variable $user existe (edición), la contraseña no es obligatoria --}}
           {{ !isset($user) ? 'required' : '' }}>
    @error('password')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
    @isset($user)
        <small class="form-text text-muted">@lang('user.password_help_text')</small>
    @endisset
</div>

{{-- Campo Confirmar Contraseña --}}
<div class="form-group">
    <label for="password_confirmation">@lang('user.confirm_password')</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
           {{ !isset($user) ? 'required' : '' }}>
</div>