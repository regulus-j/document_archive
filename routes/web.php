<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
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

Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class);

    Route::resource('documents', DocumentController::class);

    Route::resource('folders', FolderController::class);

    Route::resource('teams', TeamController::class);

    Route::resource('reports', ReportController::class);

    Route::resource('backup', BackupController::class);

    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    Route::post('/teams/search', [TeamController::class, 'ajaxSearch'])->name('teams.search');
    
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');

    Route::post('/users', [UserController::class, 'search'])->name('users.search');

    Route::post('/documents/search', [DocumentController::class, 'search'])->name('documents.search');

});

Route::get('documents/download/{id}', [DocumentController::class, 'downloadFile'])->name('documents.downloadFile');

// Route::get('/testroute', function() {
//     $name = "Funny Coder";

//     // The email sending is done using the to method on the Mail facade
//     Mail::to('jamalalbadi03@gmail.com')->send(new TestMail($name));
// });

require __DIR__.'/auth.php';
