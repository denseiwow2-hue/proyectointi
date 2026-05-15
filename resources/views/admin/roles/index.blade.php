@extends('adminlte::page')

@section('title', 'Roles y Permisos')

@section('content_header')
    <h1>Roles y Permisos</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nuevo Rol
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Roles del Sistema</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Permisos</th>
                                <th>Usuarios</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td><strong>{{ $role->name }}</strong></td>
                                    <td>
                                        @foreach($role->permissions as $permission)
                                            <span class="badge badge-info">{{ $permission->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $role->users->count() }}</td>
                                    <td>
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permisos Disponibles</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-6 mb-2">
                                <span class="badge badge-secondary">{{ $permission->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Usuarios por Rol</h3>
                </div>
                <div class="card-body">
                    @foreach($roles as $role)
                        <div class="mb-3">
                            <strong>{{ $role->name }}:</strong>
                            @if($role->users->count() > 0)
                                <ul class="list-unstyled ml-3">
                                    @foreach($role->users as $user)
                                        <li>• {{ $user->name }} ({{ $user->bloque ?? 'Sin bloque' }})</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Sin usuarios asignados</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop