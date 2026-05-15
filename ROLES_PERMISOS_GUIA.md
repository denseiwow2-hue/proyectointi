# Guía de Roles y Permisos - Proyecto Inti

## Instalación Completada ✅

Se ha implementado un sistema completo de roles y permisos usando **Spatie Laravel Permission**.

---

## 📋 Roles Creados

- **admin**: Control total del sistema
- **usuario**: Acceso limitado a funcionalidades básicas

---

## 🔐 Permisos Disponibles

### Documentos
- `ver documentos` - Listar documentos
- `crear documentos` - Crear nuevos documentos
- `editar documentos` - Modificar documentos
- `eliminar documentos` - Borrar documentos

### Imágenes
- `ver imágenes` - Listar imágenes
- `crear imágenes` - Subir imágenes
- `editar imágenes` - Modificar imágenes
- `eliminar imágenes` - Borrar imágenes

### Usuarios
- `ver usuarios` - Listar usuarios (solo admin)
- `crear usuarios` - Crear usuarios (solo admin)
- `editar usuarios` - Modificar usuarios (solo admin)
- `eliminar usuarios` - Borrar usuarios (solo admin)

---

## 👥 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@example.com | password | admin |
| user@example.com | password | usuario |

---

## 🛠️ Cómo Usar en Tu Código

### 1. Verificar Rol de un Usuario

```php
// En el controlador o vista
if (auth()->user()->hasRole('admin')) {
    // El usuario es admin
}

if (auth()->user()->hasRole('usuario')) {
    // El usuario es usuario normal
}

// Verificar si tiene alguno de varios roles
if (auth()->user()->hasAnyRole(['admin', 'editor'])) {
    // ...
}
```

### 2. Verificar Permisos de un Usuario

```php
// Verificar un permiso específico
if (auth()->user()->can('crear documentos')) {
    // El usuario puede crear documentos
}

if (auth()->user()->hasPermissionTo('editar documentos')) {
    // Alternativa más explícita
}

// Verificar si tiene alguno de varios permisos
if (auth()->user()->can('editar documentos') || auth()->user()->can('crear documentos')) {
    // ...
}
```

### 3. En Rutas (routes/web.php)

```php
// Proteger rutas por rol
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/usuarios', [UserController::class, 'index']);
    Route::post('/admin/usuarios', [UserController::class, 'store']);
});

// Proteger rutas por permiso
Route::middleware(['auth', 'permission:crear documentos'])->group(function () {
    Route::post('/documentos', [DocumentController::class, 'store']);
});

// Ambas condiciones
Route::middleware(['auth', 'role:admin', 'permission:editar usuarios'])->group(function () {
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
});
```

### 4. En Controladores

```php
public function store(Request $request)
{
    // Verificar permiso antes de ejecutar
    if (!auth()->user()->can('crear documentos')) {
        abort(403, 'No tienes permiso para crear documentos');
    }
    
    // Crear documento...
}

// Usar autorización de Laravel
public function update(Request $request, Document $document)
{
    $this->authorize('editar documentos');
    
    // Actualizar documento...
}
```

### 5. En Vistas Blade

```blade
<!-- Mostrar solo si el usuario tiene el rol -->
@role('admin')
    <a href="/admin">Panel de Administrador</a>
@endrole

<!-- Mostrar solo si tiene el permiso -->
@can('crear documentos')
    <button class="btn btn-primary">Subir Documento</button>
@endcan

<!-- Mostrar si NO tiene permiso -->
@cannot('eliminar documentos')
    <p>No puedes eliminar documentos</p>
@endcannot
```

---

## 🔧 Cómo Asignar Roles y Permisos

### Asignar rol a un usuario

```php
$user = User::find(1);
$user->assignRole('admin');
$user->assignRole(['admin', 'usuario']); // Múltiples roles
```

### Remover rol

```php
$user->removeRole('admin');
$user->syncRoles(['usuario']); // Solo este rol
```

### Asignar permisos directamente

```php
$user->givePermissionTo('crear documentos');
$user->givePermissionTo(['crear documentos', 'editar documentos']);
```

### Remover permisos

```php
$user->revokePermissionTo('eliminar documentos');
$user->syncPermissions(['crear documentos']); // Solo estos
```

---

## 📝 Ejemplos Prácticos

### Ejemplo 1: Controlador de Documentos

```php
<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $this->authorize('ver documentos');
        return view('documentos.index', [
            'documentos' => Document::all()
        ]);
    }

    public function create()
    {
        $this->authorize('crear documentos');
        return view('documentos.create');
    }

    public function store(Request $request)
    {
        $this->authorize('crear documentos');
        
        $documento = Document::create($request->validated());
        
        return redirect()->route('documentos.show', $documento);
    }

    public function edit(Document $documento)
    {
        $this->authorize('editar documentos');
        return view('documentos.edit', compact('documento'));
    }

    public function update(Request $request, Document $documento)
    {
        $this->authorize('editar documentos');
        
        $documento->update($request->validated());
        
        return redirect()->route('documentos.show', $documento);
    }

    public function destroy(Document $documento)
    {
        $this->authorize('eliminar documentos');
        
        $documento->delete();
        
        return redirect()->route('documentos.index');
    }
}
```

### Ejemplo 2: Rutas Protegidas

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AdminController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    
    // Documentos - Acceso basado en permisos
    Route::resource('documentos', DocumentController::class)
        ->middleware('permission:ver documentos');
    
    // Admin - Solo para administradores
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/usuarios', [AdminController::class, 'usuarios']);
        Route::get('/reportes', [AdminController::class, 'reportes']);
    });
});
```

---

## 🔍 Verificar Estado

Para ver los roles y permisos asignados:

```php
// En tinker (php artisan tinker)
$user = User::find(1);
$user->getRoleNames();        // Obtener todos los roles
$user->getPermissionNames();  // Obtener todos los permisos
$user->hasRole('admin');      // Verificar rol
$user->can('crear documentos'); // Verificar permiso
```

---

## 🚀 Próximos Pasos

1. **Crear controladores** para gestionar documentos e imágenes
2. **Actualizar las rutas** en `routes/web.php` con protección
3. **Crear vistas** con directivas `@can`, `@role`
4. **Crear nuevos roles y permisos** según necesites
5. **Implementar panel de administración** en AdminLTE para gestionar permisos

---

## ⚙️ Gestionar Roles y Permisos Dinámicamente

### Crear nuevo rol

```php
use Spatie\Permission\Models\Role;

$role = Role::create(['name' => 'editor']);
```

### Crear nuevo permiso

```php
use Spatie\Permission\Models\Permission;

$permission = Permission::create(['name' => 'publicar articulos']);
```

### Asignar permiso a rol

```php
$role = Role::findByName('editor');
$permission = Permission::findByName('publicar articulos');

$role->givePermissionTo($permission);
```

---

¡Tu sistema de roles y permisos está listo para usar! 🎉
