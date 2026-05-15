<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LinkSubmissionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AdminReportController;

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/submissions', [LinkSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/create', [LinkSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions', [LinkSubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}', [LinkSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/submit', [LinkSubmissionController::class, 'submit'])->name('submissions.submit');
    Route::patch('/submissions/{submission}/approve', [LinkSubmissionController::class, 'approve'])->name('submissions.approve');
    Route::patch('/submissions/{submission}/reject', [LinkSubmissionController::class, 'reject'])->name('submissions.reject');
    Route::put('/submissions/{submission}', [LinkSubmissionController::class, 'update'])->name('submissions.update');

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // Usuarios
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

        // Roles
        Route::get('/roles', [AdminRoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/create', [AdminRoleController::class, 'create'])->name('admin.roles.create');
        Route::post('/roles', [AdminRoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/roles/{role}', [AdminRoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])->name('admin.roles.destroy');

        // Reportes
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
    });
});

Route::middleware(['auth', 'role:admin'])->get('/users', function () {
    return redirect()->route('admin.users.index');
});
