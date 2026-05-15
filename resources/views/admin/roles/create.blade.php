@extends('adminlte::page')

@section('title', 'Crear Rol')

@section('content_header')
    <h1>Crear Nuevo Rol</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Datos del Rol</h3>
        </div>
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nombre del Rol *</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Ejemplo: moderador, editor, supervisor</small>
                </div>

                <div class="form-group">
                    <label>Permisos</label>
                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Crear Rol</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@stop