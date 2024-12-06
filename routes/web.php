<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {
Route::middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);

    Route::prefix('users')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/search', [UserController::class, 'search'])->name('users.search');
    });

    Route::prefix('documents')->group(function () {
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');


        // Move the 'pending' route here
        Route::get('/pending', [DocumentController::class, 'showPending'])->name('documents.pending');

        Route::delete('/attachments/{id}', [DocumentController::class, 'deleteAttachment'])->name('attachments.delete');

        Route::get('/terminal', [DocumentController::class, 'tagTerminal'])->name('documents.terminal');

        // Parameterized routes should come after static routes
        Route::get('/edit/{document}', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        //receive and release
        Route::get('/receive/{document}', [DocumentController::class, 'setReceived'])->name('documents.receive');
        Route::get('/release/{document}', [DocumentController::class, 'confirmReleased'])->name('documents.confirmrelease');
        Route::put('/release/{document}', [DocumentController::class, 'setReleased'])->name('documents.release');
        Route::get('/tag-terminal/{document}', [DocumentController::class, 'tagAsTerminal'])->name('documents.tagterminal');
        Route::get('/retract-terminal/{document}', [DocumentController::class, 'retractTerminal'])->name('documents.retractterminal');

        Route::post('/search', [DocumentController::class, 'search'])->name('documents.search');
        Route::get('/search', [DocumentController::class, 'download'])->name('documents.download');
    });

    Route::prefix('office')->group(function () {
    Route::prefix('office')->group(function () {
        Route::get('/', [OfficeController::class, 'index'])->name('office.index');
        Route::get('/create', [OfficeController::class, 'create'])->name('office.create');
        Route::post('/', [OfficeController::class, 'store'])->name('office.store');
        Route::get('/{office}', [OfficeController::class, 'show'])->name('office.show');
        Route::get('/{office}/edit', [OfficeController::class, 'edit'])->name('office.edit');
        Route::put('/{office}', [OfficeController::class, 'update'])->name('office.update');
        Route::delete('/{office}', [OfficeController::class, 'destroy'])->name('office.destroy');
    });

    Route::prefix('reports')->group(function () {
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
        Route::post('/generate', [ReportController::class, 'generate'])->name('reports.generate');
    });

    Route::prefix('backup')->group(function () {
    Route::prefix('backup')->group(function () {
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

require __DIR__ . '/auth.php';
