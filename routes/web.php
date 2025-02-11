<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/profile/set-password', function () {
    //     return view('profile.setPassword');
    // })->name('profile.set');

    // Route::put('/profile/set-password', [ProfileController::class, 'set'])->name('profile.set');
});


//----------------------------------------------------------------------------------------------------------------
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
Route::post('/plans/{plan}/subscribe', [PlanController::class, 'subscribe'])->name('plans.subscribe');

Route::post('/subscriptions', [SubscriptionController::class, 'store']);
Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update']);
Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
Route::post('/subscriptions/{subscription}/activate', [SubscriptionController::class, 'activate']);

Route::middleware(['auth'])->group(function () {
    Route::resource('payments', PaymentController::class)->only(['index', 'show']);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('addresses', AddressController::class);
});

//--------------------------------------------------------------------------------------------------------------------

Route::middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);

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
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');

        // Static routes
        Route::get('/pending', [DocumentController::class, 'showPending'])->name('documents.pending');
        Route::get('/complete', [DocumentController::class, 'showComplete'])->name('documents.complete');
        Route::delete('/attachments/{id}', [DocumentController::class, 'deleteAttachment'])->name('attachments.delete');

        // Parameterized routes
        Route::get('/{document}/show', [DocumentController::class, 'show'])->name('documents.show');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/{document}/delete', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::delete('/{document}/delete-attachment', [DocumentController::class, 'deleteAttachment'])->name('documents.attachments.destroy');

        // Update status route
        Route::get('/{document}/status', [DocumentController::class, 'confirmReleased'])->name('documents.confirmrelease');
        Route::get('/{document}/{status}', [DocumentController::class, 'changeStatus'])->name('documents.changeStatus');

        Route::put('/{document}/{status}', [DocumentController::class, 'changeStatus'])->name('documents.updateStatus');

        Route::post('/search', [DocumentController::class, 'search'])->name('documents.search');
        Route::get('/{id}/download', [DocumentController::class, 'downloadFile'])->name('documents.download');
    });

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
        Route::get('/', [BackupController::class, 'index'])->name('backup.index');
        Route::get('/create', [BackupController::class, 'create'])->name('backup.create');
        Route::post('/', [BackupController::class, 'store'])->name('backup.store');
        Route::get('/{backup}', [BackupController::class, 'show'])->name('backup.show');
        Route::get('/{backup}/edit', [BackupController::class, 'edit'])->name('backup.edit');
        Route::put('/{backup}', [BackupController::class, 'update'])->name('backup.update');
        Route::delete('/{backup}', [BackupController::class, 'destroy'])->name('backup.destroy');
    });
});

//stmp mail test
// Route::get('/testroute', function() {
//     $name = "Funny Coder";

//     // The email sending is done using the to method on the Mail facade
//     Mail::to('jamalalbadi03@gmail.com')->send(new TestMail($name));
// });

require __DIR__ . '/auth.php';
