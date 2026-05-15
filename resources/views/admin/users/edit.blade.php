@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar Usuario: {{ $user->name }}</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Datos del Usuario</h3>
        </div>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre Completo *</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ci">CI</label>
                            <input type="text" id="ci" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci', $user->ci) }}">
                            @error('ci')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bloque">Bloque</label>
                            <input type="text" id="bloque" name="bloque" class="form-control @error('bloque') is-invalid @enderror" value="{{ old('bloque', $user->bloque) }}">
                            @error('bloque')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Rol *</label>
                            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}" {{ (old('role', $userRole?->name) === $roleOption->name) ? 'selected' : '' }}>
                                        {{ ucfirst($roleOption->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Correo Electrónico *</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Nueva Contraseña (dejar vacío para mantener)</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Actualizar Usuario</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
                    </form>
                @endif
            </div>
        </form>
    </div>
@stop