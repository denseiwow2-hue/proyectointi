@if(auth()->user()->hasAnyRole(['admin', 'encargado']))
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tarea #{{ $submission->id }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Usuario:</strong> {{ $submission->user ? $submission->user->name . ' (' . $submission->user->bloque . ')' : 'Usuario eliminado' }}</p>
            <p><strong>Plataforma:</strong> {{ ucfirst($submission->platform) }}</p>
            <p><strong>Link:</strong> <a href="{{ $submission->url }}" target="_blank">{{ $submission->url }}</a></p>
            <p><strong>Acción requerida:</strong> {{ $submission->action }}</p>
            <p><strong>Bloque:</strong> {{ $submission->user ? $submission->user->bloque : 'N/A' }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($submission->status) }}</p>
            <p><strong>Asignado por:</strong> {{ $submission->assignedBy?->name ?? 'Sistema' }}</p>
            <p><strong>Creado el:</strong> {{ $submission->created_at->format('d/m/Y H:i') }}</p>
            @if($submission->due_date)
                <p><strong>Fecha límite:</strong> {{ $submission->due_date->format('d/m/Y') }}</p>
            @endif
            @if($submission->image_path)
                <p><strong>Imagen subida:</strong></p>
                <div class="row">
                    @foreach($submission->image_path as $path)
                        <div class="col-md-4 mb-3">
                            <img src="{{ asset('storage/' . $path) }}" alt="Imagen subida" class="img-fluid">
                        </div>
                    @endforeach
                </div>
            @endif
            @if($submission->admin_comment)
                <div class="alert alert-info">
                    <strong>Comentario del administrador:</strong>
                    <p>{{ $submission->admin_comment }}</p>
                </div>
            @endif
            @if($submission->user_comment)
                <div class="alert alert-secondary">
                    <strong>Comentario del usuario:</strong>
                    <p>{{ $submission->user_comment }}</p>
                </div>
            @endif
        </div>
    </div>

    @if($submission->status === 'subido')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Acciones de administrador</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('submissions.approve', $submission) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="admin_comment">Comentario (opcional)</label>
                        <textarea id="admin_comment" name="admin_comment" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Aprobar</button>
                </form>
                <form method="POST" action="{{ route('submissions.reject', $submission) }}" class="d-inline ml-2">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="admin_comment_reject">Comentario (requerido)</label>
                        <textarea id="admin_comment_reject" name="admin_comment_reject" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Rechazar</button>
                </form>
            </div>
        </div>
    @endif
@else
    @if($submission->status === 'pendiente')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ ucfirst($submission->platform) }}</h3>
            </div>
            <div class="card-body">
                <p><strong>Link:</strong> <a href="{{ $submission->url }}" target="_blank">{{ $submission->url }}</a></p>
                <p><strong>Acción requerida:</strong> {{ $submission->action }}</p>
                @if($submission->due_date)
                    <p><strong>Fecha límite:</strong> {{ $submission->due_date->format('d/m/Y') }}</p>
                @endif
                <hr>
                <form method="POST" action="{{ route('submissions.submit', $submission) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="images">Subir imágenes (puedes seleccionar múltiples manteniendo Ctrl mientras haces click, o pegar con Ctrl+V)</label>
                        <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple required onchange="previewImages(this)">
                        <div id="imagePreviews" style="margin-top: 10px;"></div>
                    </div>
                    <div class="form-group">
                        <label for="user_comment">Comentario (opcional)</label>
                        <textarea id="user_comment" name="user_comment" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Subir tarea</button>
                </form>
                <script>
                    function previewImages(input) {
                        var previewsDiv = document.getElementById('imagePreviews');
                        previewsDiv.innerHTML = ''; // Limpiar previews anteriores
                        if (input.files && input.files.length > 0) {
                            for (var i = 0; i < input.files.length; i++) {
                                var file = input.files[i];
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    var img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.alt = 'Vista previa';
                                    img.style.maxWidth = '200px';
                                    img.style.margin = '5px';
                                    img.className = 'img-fluid';
                                    previewsDiv.appendChild(img);
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    }

                    // Permitir pegar imágenes con Ctrl+V
                    document.addEventListener('paste', function(e) {
                        var input = document.getElementById('images');
                        var items = e.clipboardData.items;
                        var dt = new DataTransfer();
                        var hasImages = false;

                        for (var i = 0; i < items.length; i++) {
                            if (items[i].type.indexOf('image') !== -1) {
                                var file = items[i].getAsFile();
                                dt.items.add(file);
                                hasImages = true;
                            }
                        }

                        if (hasImages) {
                            // Agregar las imágenes pegadas a las ya seleccionadas
                            for (var i = 0; i < input.files.length; i++) {
                                dt.items.add(input.files[i]);
                            }
                            input.files = dt.files;
                            previewImages(input);
                            e.preventDefault(); // Evitar pegar en otros lugares
                        }
                    });
                </script>
            </div>
        </div>
    @elseif($submission->status === 'subido')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ ucfirst($submission->platform) }}</h3>
            </div>
            <div class="card-body">
                <p class="text-info">Tarea subida, esperando revisión.</p>
                @if($submission->image_path)
                    <p><strong>Imágenes subidas:</strong></p>
                    <div class="row">
                        @foreach($submission->image_path as $path)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $path) }}" alt="Imagen subida" class="img-fluid">
                            </div>
                        @endforeach
                    </div>
                @endif
                @if($submission->user_comment)
                    <div class="alert alert-secondary">
                        <strong>Tu comentario:</strong>
                        <p>{{ $submission->user_comment }}</p>
                    </div>
                @endif
            </div>
        </div>
    @elseif($submission->status === 'aprobado')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ ucfirst($submission->platform) }}</h3>
            </div>
            <div class="card-body">
                <p class="text-success">Tarea aprobada.</p>
                @if($submission->admin_comment)
                    <div class="alert alert-info">
                        <strong>Comentario del administrador:</strong>
                        <p>{{ $submission->admin_comment }}</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ ucfirst($submission->platform) }}</h3>
            </div>
            <div class="card-body">
                <p class="text-danger">Tarea rechazada.</p>
                @if($submission->admin_comment)
                    <div class="alert alert-danger">
                        <strong>Comentario del administrador:</strong>
                        <p>{{ $submission->admin_comment }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endif