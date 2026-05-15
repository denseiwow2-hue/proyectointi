@extends('adminlte::page')

@section('title', 'Tareas de Revisión')

@section('css')
    <style>
        .card-body { padding: 0.75rem; }
        .table-responsive { overflow-x: auto; }
        #submissions-table th, #submissions-table td { vertical-align: middle; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.4rem 0.75rem; }
        .btn-primary.btn-sm { min-width: 100px; }
        @media (max-width: 768px) {
            .card-header .card-title { font-size: 1rem; }
            .card-body { padding: 0.75rem 0.65rem; }
            #submissions-table {
                border: 0;
            }
            #submissions-table thead { display: none; }
            #submissions-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid rgba(0,0,0,0.08);
                border-radius: 14px;
                background: #fff;
                box-shadow: 0 10px 24px rgba(15,23,42,0.06);
                padding: 0.75rem;
            }
            #submissions-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0.45rem;
                white-space: normal;
                border: 0;
            }
            #submissions-table tbody td::before {
                content: attr(data-label) ":";
                flex: 1 1 40%;
                color: #6b7280;
                font-weight: 700;
                padding-right: 0.75rem;
                white-space: normal;
                text-transform: uppercase;
                letter-spacing: 0.02em;
            }
            #submissions-table tbody td:last-child {
                justify-content: flex-end;
            }
            .modal-dialog { max-width: 95%; margin: 1.5rem auto; }
            .modal-content { border-radius: 18px; }
            .open-modal { width: 100%; }
        }
    </style>
@stop

@section('content_header')
    <h1>Tareas de Revisión</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de tareas</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table id="submissions-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Plataforma</th>
                        <th>Acción</th>
                        <th>Estado</th>
                        <th>Bloque</th>
                        <th>Fecha</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr>
                            <td data-label="ID">{{ $submission->id }}</td>
                            <td data-label="Usuario">{{ $submission->user->name }}</td>
                            <td data-label="Plataforma">{{ ucfirst($submission->platform) }}</td>
                            <td data-label="Acción">{{ $submission->action }}</td>
                            <td data-label="Estado">
                                <span class="badge badge-{{ $submission->status === 'pendiente' ? 'warning' : ($submission->status === 'subido' ? 'info' : ($submission->status === 'aprobado' ? 'success' : 'danger')) }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td data-label="Bloque">{{ $submission->user->bloque ?? '—' }}</td>
                            <td data-label="Fecha">{{ $submission->created_at->format('d/m/Y') }}</td>
                            <td data-label="Ver"><button type="button" class="btn btn-sm btn-primary open-modal" data-id="{{ $submission->id }}">Abrir</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No hay tareas disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="submissionModal" tabindex="-1" role="dialog" aria-labelledby="submissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submissionModalLabel">@if(auth()->user()->hasAnyRole(['admin', 'encargado'])) Detalles de la Tarea @else Subir Tarea @endif</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Contenido se cargará aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function () {

        // DataTable
        $('#submissions-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            order: [[0, 'desc']]
        });

        // Modal AJAX
        $('.open-modal').on('click', function() {
            var submissionId = $(this).data('id');

            $.ajax({
                url: '{{ route("submissions.show", ":id") }}'.replace(':id', submissionId),
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    $('#modalContent').html(data);
                    $('#submissionModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log('Error AJAX:', xhr.responseText);
                    alert('Error al cargar los detalles: ' + xhr.status + ' - ' + error);
                }
            });
        });

        // Cerrar modal al enviar form
        $(document).on('submit', '#modalContent form', function() {
            $('#submissionModal').modal('hide');
        });

    });
</script>
@stop
