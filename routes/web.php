<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/profile/set-password', function () {
    //     return view('profile.setPassword');
    // })->name('profile.set');

    // Route::put('/profile/set-password', [ProfileController::class, 'set'])->name('profile.set');
});

Route::middleware('auth')->group(function() {
    Route::resource('roles', RoleController::class);

    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/search', [UserController::class, 'search'])->name('users.search');
    });

    Route::prefix('documents')->group(function() {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::post('/search', [DocumentController::class, 'search'])->name('documents.search');
        // Route::get('/audit', [DocumentController::class, 'audit'])->name('documents.audit');
    });

    Route::prefix('folders')->group(function() {
        Route::get('/', [FolderController::class, 'index'])->name('folders.index');
        Route::get('/create', [FolderController::class, 'create'])->name('folders.create');
        Route::post('/', [FolderController::class, 'store'])->name('folders.store');
        Route::get('/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::get('/{folder}/edit', [FolderController::class, 'edit'])->name('folders.edit');
        Route::put('/{folder}', [FolderController::class, 'update'])->name('folders.update');
        Route::delete('/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    });

    Route::prefix('office')->group(function() {
        Route::get('/', [OfficeController::class, 'index'])->name('office.index');
        Route::get('/create', [OfficeController::class, 'create'])->name('office.create');
        Route::post('/', [OfficeController::class, 'store'])->name('office.store');
        Route::get('/{office}', [OfficeController::class, 'show'])->name('office.show');
        Route::get('/{office}/edit', [OfficeController::class, 'edit'])->name('office.edit');
        Route::put('/{office}', [OfficeController::class, 'update'])->name('office.update');
        Route::delete('/{office}', [OfficeController::class, 'destroy'])->name('office.destroy');
    });

    Route::prefix('reports')->group(function() {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
        Route::post('/generate', [ReportController::class, 'generate'])->name('reports.generate');
    });

    Route::prefix('backup')->group(function() {
        Route::get('/', [BackupController::class, 'index'])->name('backup.index');
        Route::get('/create', [BackupController::class, 'create'])->name('backup.create');
        Route::post('/', [BackupController::class, 'store'])->name('backup.store');
        Route::get('/{backup}', [BackupController::class, 'show'])->name('backup.show');
        Route::get('/{backup}/edit', [BackupController::class, 'edit'])->name('backup.edit');
        Route::put('/{backup}', [BackupController::class, 'update'])->name('backup.update');
        Route::delete('/{backup}', [BackupController::class, 'destroy'])->name('backup.destroy');
    });
});

Route::get('documents/download/{id}', [DocumentController::class, 'downloadFile'])->name('documents.downloadFile');

//stmp mail test
// Route::get('/testroute', function() {
//     $name = "Funny Coder";

//     // The email sending is done using the to method on the Mail facade
//     Mail::to('jamalalbadi03@gmail.com')->send(new TestMail($name));
// });

require __DIR__.'/auth.php';
