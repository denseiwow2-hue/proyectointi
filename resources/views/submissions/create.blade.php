@extends('adminlte::page')

@section('title', 'Crear Tarea')

@section('content_header')
    <h1>Crear nueva tarea</h1>
@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Asignar tarea por rol</h3>
        </div>
        <form method="POST" action="{{ route('submissions.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="">Selecciona un rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="platform">Plataforma</label>
                    <select name="platform" id="platform" class="form-control" required>
                        <option value="tiktok">TikTok</option>
                        <option value="facebook">Facebook</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="url">Link de TikTok o Facebook</label>
                    <input type="url" id="url" name="url" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="action">Acción requerida</label>
                    <select name="action" id="action" class="form-control" required>
                        <option value="">Selecciona una acción</option>
                        <option value="like-comentario">Like y Comentario</option>
                        <option value="denuncia">Denuncia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="due_date">Fecha límite</label>
                    <input type="date" id="due_date" name="due_date" class="form-control">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar tarea</button>
            </div>
        </form>
    </div>
@stop
