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
use App\Http\Controllers\PlanSelectionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CompanyController;
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
Route::get('/register/{plan}', [PlanController::class, 'register'])->name('plans.register');
Route::post('/plans/{plan}/subscribe', [PlanController::class, 'subscribe'])->name('plans.subscribe');

Route::middleware(['auth'])->group(function () {
    Route::get('/plans/select', [PlanSelectionController::class, 'select'])->name('plans.select');
    Route::post('/plans/store', [PlanSelectionController::class, 'store'])->name('plans.store');

    // Payment routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create/{plan}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/{plan}', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');

    // Subscription routes
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscriptions/{subscription}/activate', [SubscriptionController::class, 'activate'])->name('subscriptions.activate');
});




Route::middleware(['auth'])->group(function () {
    Route::resource('addresses', AddressController::class);
});

//--------------------------------------------------------------------------------------------------------------------

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/users/registered', [UserController::class, 'showRegistered'])->name('users.registered');
    Route::get('/plans', [PlanController::class, 'index'])->name('admin.plans.index');
    Route::get('/subscriptions', [SubscriptionController::class, 'indexAdmin'])->name('admin.subscriptions.index');
});

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

    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/{company}',  [CompanyController::class, 'show'])->name('companies.show');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');

        Route::get('/managed/{user}', [CompanyController::class, 'userCompanies'])->name('companies.userManaged');

        Route::prefix('{company}/addresses')->group(function () {
            Route::get('/', [CompanyController::class, 'addresses'])->name('companies.addresses.index');
            Route::get('/create', [CompanyController::class, 'createAddress'])->name('companies.addresses.create');
            Route::post('/', [CompanyController::class, 'storeAddress'])->name('companies.addresses.store');
            Route::get('/{address}/edit', [CompanyController::class, 'editAddress'])->name('companies.addresses.edit');
            Route::put('/{address}', [CompanyController::class, 'updateAddress'])->name('companies.addresses.update');
            Route::delete('/{address}', [CompanyController::class, 'destroyAddress'])->name('companies.addresses.destroy');
        });
    });

    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'uploadController'])->name('documents.store');

        // Static routes
        Route::get('/archive', [DocumentController::class, 'showArchive'])->name('documents.archive');
        Route::get('/released', [DocumentController::class, 'showReleased'])->name('documents.released');
        Route::get('/pending', [DocumentController::class, 'showPending'])->name('documents.pending');
        Route::get('/complete', [DocumentController::class, 'showComplete'])->name('documents.complete');
        Route::delete('/attachments/{id}', [DocumentController::class, 'deleteAttachment'])->name('attachments.delete');
        Route::get('/forward/{document}', [DocumentController::class, 'forwardDocument'])->name('documents.forward');

        Route::get('/workflows', [DocumentController::class, 'workflowManagement'])->name('documents.workflows');
        Route::get('/workflows/{workflow}/receive', [DocumentController::class, 'receiveWorkflow'])->name('documents.receive');
        Route::get('/workflows/{workflow}', [DocumentController::class, 'approveWorkflow'])->name('documents.approveWorkflow');
        Route::get('/workflows/{workflow}/reject', [DocumentController::class, 'rejectWorkflow'])->name('documents.rejectWorkflow');
        Route::get('/workflows/{workflow}/review', [DocumentController::class, 'reviewDocument'])->name('documents.review');

        // Parameterized routes
        Route::get('/{document}/show', [DocumentController::class, 'show'])->name('documents.show');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::post('/{document}/forward', [DocumentController::class, 'forwardDocumentSubmit'])->name('documents.forward.submit');
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
        Route::get('/reports/analytics', [ReportController::class, 'analytics'])->name('reports.analytics');
        Route::get('/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
        Route::post('/generate', [ReportController::class, 'generate'])->name('reports.generate');
    });

    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/{plan}/subscribe', [PlanController::class, 'subscribe'])->name('plans.subscribe');

    Route::get('/plans/{plan}', [PlanController::class, 'show'])->name('plans.show');
    Route::get('/plans/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');

    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update']);
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
    Route::post('/subscriptions/{subscription}/activate', [SubscriptionController::class, 'activate']);

    Route::get('/pay/{plan}/{billing?}', [PaymentController::class, 'linkCreate'])->name('payment.generate');
    Route::get('/check-payment-status/{reference}', function ($reference) {
        $controller = app(\App\Http\Controllers\PaymentController::class);
        return response()->json($controller->checkPaymentStatus($reference));
    })->name('payment.check-status')->middleware('web');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');



    Route::middleware(['auth'])->group(function () {
        Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    });

    Route::middleware(['auth'])->group(function () {
        Route::resource('addresses', AddressController::class);
    });
});

//stmp mail test
// Route::get('/testroute', function() {
//     $name = "Funny Coder";

//     // The email sending is done using the to method on the Mail facade
//     Mail::to('jamalalbadi03@gmail.com')->send(new TestMail($name));
// });

require __DIR__ . '/auth.php';
