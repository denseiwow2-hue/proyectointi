@extends('adminlte::page')

@section('title', 'Reportes y Estadísticas')

@section('content_header')
    <h1>Reportes y Estadísticas</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Porcentaje de Aprobación por Bloque</h3>
                </div>
                <div class="card-body">
                    <canvas id="blockChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Estadísticas por Bloque</h3>
            @if($selectedBlock)
                <div class="card-tools">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm">Ver Todos los Bloques</a>
                </div>
            @endif
        </div>
        <div class="card-body">
            <table id="blocksTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Bloque</th>
                        <th>Total Usuarios</th>
                        <th>Total Tareas</th>
                        <th>Pendientes</th>
                        <th>Esperando Revisión</th>
                        <th>Aprobadas</th>
                        <th>Rechazadas</th>
                        <th>% Aprobación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blockStats as $block)
                        <tr>
                            <td>{{ $block['bloque'] }}</td>
                            <td>{{ $block['total_usuarios'] }}</td>
                            <td>{{ $block['total_tareas'] }}</td>
                            <td>{{ $block['pendiente'] }}</td>
                            <td>{{ $block['subido'] }}</td>
                            <td>{{ $block['aprobado'] }}</td>
                            <td>{{ $block['rechazado'] }}</td>
                            <td>
                                <span class="badge badge-{{ $block['porcentaje_aprobado'] >= 70 ? 'success' : ($block['porcentaje_aprobado'] >= 50 ? 'warning' : 'danger') }}">
                                    {{ $block['porcentaje_aprobado'] }}%
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.reports.index', ['bloque' => $block['bloque']]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Ver Detalles
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($selectedBlock && $blockUsers->isNotEmpty())
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Usuarios del Bloque: {{ $selectedBlock }}</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="userChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Estadísticas del Bloque</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="blockDetailChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Usuarios del Bloque: {{ $selectedBlock }}</h3>
            </div>
            <div class="card-body">
                @if($blockUsers->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay usuarios en este bloque o no tienen tareas asignadas.
                    </div>
                @else
                <table id="usersTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>CI</th>
                            <th>Total Tareas</th>
                            <th>Pendientes</th>
                            <th>Esperando Revisión</th>
                            <th>Aprobadas</th>
                            <th>Rechazadas</th>
                            <th>% Aprobación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blockUsers as $user)
                            <tr>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['ci'] }}</td>
                                <td>{{ $user['total_tareas'] }}</td>
                                <td>{{ $user['pendiente'] }}</td>
                                <td>{{ $user['subido'] }}</td>
                                <td>{{ $user['aprobado'] }}</td>
                                <td>{{ $user['rechazado'] }}</td>
                                <td>
                                    <span class="badge badge-{{ $user['porcentaje_aprobado'] >= 70 ? 'success' : ($user['porcentaje_aprobado'] >= 50 ? 'warning' : 'danger') }}">
                                        {{ $user['porcentaje_aprobado'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    @elseif($selectedBlock)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Usuarios del Bloque: {{ $selectedBlock }}</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No hay usuarios en este bloque o no tienen tareas asignadas.
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <style>
        /* Estilos para las tablas y gráficos */
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de bloques
        const blockLabels = @json($blockPercentages->keys()->toArray());
        const blockData = @json($blockPercentages->values()->toArray());

        new Chart(document.getElementById('blockChart'), {
            type: 'bar',
            data: {
                labels: blockLabels,
                datasets: [{
                    label: '% Aprobación',
                    data: blockData,
                    backgroundColor: '#3c8dbc',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        @if($selectedBlock && $blockUsers->isNotEmpty())
            // Gráfico de usuarios del bloque
            const userLabels = @json($userStats->keys()->toArray());
            const userData = @json($userStats->values()->toArray());

            new Chart(document.getElementById('userChart'), {
                type: 'bar',
                data: {
                    labels: userLabels,
                    datasets: [{
                        label: '% Aprobación',
                        data: userData,
                        backgroundColor: '#00a65a',
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Gráfico detallado del bloque
            const blockDetailData = [
                {{ $blockUsers->sum('pendiente') ?? 0 }},
                {{ $blockUsers->sum('subido') ?? 0 }},
                {{ $blockUsers->sum('aprobado') ?? 0 }},
                {{ $blockUsers->sum('rechazado') ?? 0 }}
            ];

            new Chart(document.getElementById('blockDetailChart'), {
                type: 'pie',
                data: {
                    labels: ['Pendientes', 'Esperando Revisión', 'Aprobadas', 'Rechazadas'],
                    datasets: [{
                        data: blockDetailData,
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
        @endif

        // Inicializar DataTables
        $(document).ready(function() {
            $('#blocksTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "responsive": true,
                "pageLength": 10
            });

            @if($selectedBlock && $blockUsers->isNotEmpty())
                $('#usersTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                    },
                    "responsive": true,
                    "pageLength": 10
                });
            @endif
        });
    </script>
@stop