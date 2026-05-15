@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Panel de revisión</h1>
@stop

@section('content')
    @php $isAdmin = auth()->user()->hasAnyRole(['admin', 'encargado']); @endphp
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total }}</h3>
                    <p>{{ $isAdmin ? 'Tareas totales' : 'Tus tareas' }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pending }}</h3>
                    <p>Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $awaiting }}</h3>
                    <p>Esperando revisión</p>
                </div>
                <div class="icon">
                    <i class="fas fa-upload"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $approved }}</h3>
                    <p>Aprobadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Estado de tareas</h3>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Distribución por plataforma</h3>
                </div>
                <div class="card-body">
                    <canvas id="platformChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Resumen rápido</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><strong>Pendientes:</strong> {{ $pending }}</li>
                                <li><strong>En revisión:</strong> {{ $awaiting }}</li>
                                <li><strong>Aprobadas:</strong> {{ $approved }}</li>
                                <li><strong>Rechazadas:</strong> {{ $rejected }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <p>{{ $isAdmin ? 'Este es tu panel principal. Puedes usarlo para ver qué tareas requieren acción primero.' : 'Este es tu panel personal. Aquí ves solo tus tareas y su estado.' }}</p>
                            <p class="mb-2"><strong>Consejo:</strong> {{ $isAdmin ? 'revisa primero las tareas pendientes y subidas antes de crear nuevas.' : 'sube todas tus imágenes solicitadas y revisa si ya hay comentarios del admin.' }}</p>
                            <div class="btn-group" role="group">
                                <a href="{{ route('submissions.index', ['status' => 'pendiente']) }}" class="btn btn-warning btn-sm">Ver pendientes</a>
                                <a href="{{ route('submissions.index', ['status' => 'subido']) }}" class="btn btn-primary btn-sm">Ver en revisión</a>
                                @if($isAdmin)
                                    <a href="{{ route('submissions.create') }}" class="btn btn-success btn-sm">Crear enlace</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Tips de uso</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Usa el filtro por plataforma para ver solo Facebook o TikTok.</li>
                        <li>Sube varias imágenes para que el administrador pueda revisar mejor tu trabajo.</li>
                        @if($isAdmin)
                            <li>Si eres administrador, asigna por rol para organizar mejor a los usuarios.</li>
                            <li>Revisa la sección "Esperando revisión" antes de crear nuevas tareas.</li>
                        @else
                            <li>Para tu rol, completa las tareas pendientes y espera la revisión del admin.</li>
                            <li>Si una tarea es rechazada, revisa el comentario y vuelve a subir otra imagen.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box .icon {
            top: 12px;
            right: 10px;
        }
        .small-box h3 {
            font-size: 2.2rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statusLabels = @json(array_keys($statusCounts));
        const statusData = @json(array_values($statusCounts));
        const platformLabels = @json(array_keys($platformCounts));
        const platformData = @json(array_values($platformCounts));

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#f39c12', '#007bff', '#28a745', '#dc3545'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        new Chart(document.getElementById('platformChart'), {
            type: 'bar',
            data: {
                labels: platformLabels,
                datasets: [{
                    label: 'Tareas por plataforma',
                    data: platformData,
                    backgroundColor: ['#3c8dbc', '#00a65a'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, precision: 0 }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
@stop
