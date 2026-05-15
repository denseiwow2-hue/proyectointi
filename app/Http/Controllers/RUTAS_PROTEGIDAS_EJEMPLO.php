<?php

// Ejemplo de cómo configurar las rutas con protección de roles y permisos
// Agregar esto a: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::middleware('auth')->group(function () {
    
    // ============================================
    // Rutas de Documentos - Con permisos
    // ============================================
    
    // Ver documentos
    Route::get('/documentos', [DocumentController::class, 'index'])
        ->middleware('permission:ver documentos')
        ->name('documentos.index');
    
    // Crear documento
    Route::get('/documentos/crear', [DocumentController::class, 'create'])
        ->middleware('permission:crear documentos')
        ->name('documentos.create');
    
    Route::post('/documentos', [DocumentController::class, 'store'])
        ->middleware('permission:crear documentos')
        ->name('documentos.store');
    
    // Editar documento
    Route::get('/documentos/{id}/editar', [DocumentController::class, 'edit'])
        ->middleware('permission:editar documentos')
        ->name('documentos.edit');
    
    Route::put('/documentos/{id}', [DocumentController::class, 'update'])
        ->middleware('permission:editar documentos')
        ->name('documentos.update');
    
    // Eliminar documento
    Route::delete('/documentos/{id}', [DocumentController::class, 'destroy'])
        ->middleware('permission:eliminar documentos')
        ->name('documentos.destroy');
    
    
    // ============================================
    // Panel de Administrador - Solo para admins
    // ============================================
    
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('admin.usuarios.index');
        Route::get('/reportes', [ReportController::class, 'index'])->name('admin.reportes.index');
    });
    
});

/*
NOTAS IMPORTANTES:

1. Middleware de roles:
   - middleware('role:admin') - Solo para usuarios con rol "admin"
   - middleware('role:admin|editor') - Admin O editor
   
2. Middleware de permisos:
   - middleware('permission:crear documentos') - Solo con este permiso
   - middleware('permission:crear documentos|editar documentos') - Uno u otro

3. Combinaciones:
   - middleware('role:admin', 'permission:editar usuarios') - Ambas condiciones
   
4. Alternativa: Proteger en el controlador
   - if (!auth()->user()->can('ver documentos')) abort(403);

5. Con recurso:
   - Route::resource('documentos', DocumentController::class)
       ->middleware('permission:ver documentos');
*/
