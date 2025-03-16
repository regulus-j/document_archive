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
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PlanSelectionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Superadmin\SuperadminController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\OfficeDashboardController;


use App\Models\Company;
use App\Models\CompanyAccount;


// ------------------ Public Route ------------------
Route::get('/', function () {
    return view('welcome');
});

// ------------------ Named "dashboard" Route ------------------
Route::get('/dashboard', function () {
    if (Auth::user()->hasRole('superadmin')) {
        return redirect()->route('superadmin.dashboard');
    } elseif (Auth::user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->hasRole('user')) {
        return redirect()->route('offices.dashboard');
    } else {
        abort(403, 'Unauthorized');
    }
})->middleware(['auth'])->name('dashboard');

// ------------------ Superadmin Dashboard ------------------
Route::get('/superadmin/dashboard', [SuperadminController::class, 'dashboard'])
     ->middleware(['auth','role:superadmin'])
     ->name('superadmin.dashboard');

// ------------------ Admin Dashboard ------------------
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
     ->middleware(['auth','role:admin'])
     ->name('admin.dashboard');

// ------------------ Office Dashboard ------------------
Route::get('/offices/dashboard', [DashboardController::class, 'officeDashboard'])
     ->middleware(['auth','role:user'])
     ->name('offices.dashboard');

// ------------------ Profile Routes ------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ------------------ Offices Routes ------------------

        Route::middleware(['auth', 'role:user'])->group(function () {
            Route::get('/office/dashboard', [OfficeDashboardController::class, 'index'])->name('office.dashboard');

        
        Route::get('/', [OfficeController::class, 'index'])->name('offices.index');
        Route::get('/create', [OfficeController::class, 'create'])->name('offices.create');
        Route::post('/', [OfficeController::class, 'store'])->name('offices.store');
        Route::get('/{office}', [OfficeController::class, 'show'])->name('offices.show');
        Route::get('/{office}/edit', [OfficeController::class, 'edit'])->name('offices.edit');
        Route::put('/{office}', [OfficeController::class, 'update'])->name('offices.update');
        Route::delete('/{office}', [OfficeController::class, 'destroy'])->name('offices.destroy');
        Route::get('/offices/{office}/company', [OfficeController::class, 'hasCompany'])
     ->name('has.company');

    });

// Reports
Route::middleware(['auth', 'has.company'])->group(function () {
    Route::resource('offices', OfficeController::class);
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

// ------------------ Plan & Payment Routes ------------------
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
Route::get('/register/{plan}', [PlanController::class, 'register'])->name('plans.register');
Route::post('/plans/{plan}/subscribe', [PlanController::class, 'subscribe'])->name('plans.subscribe');
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/plans', [PlanController::class, 'index'])->name('superadmin.plans.index');


});


Route::middleware(['auth'])->group(function () {
    Route::get('/plans/select', [PlanSelectionController::class, 'select'])->name('plans.select');
    Route::post('/plans/store', [PlanSelectionController::class, 'store'])->name('plans.store');
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');


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
    Route::middleware(['auth', 'role:superadmin'])->group(function () {
        Route::get('/superadmin/subscriptions', [SubscriptionController::class, 'index'])
             ->name('superadmin.subscriptions.index');
    });
    
});

// Payment status and success routes
Route::get('/pay', [PaymentController::class, 'linkCreate'])->name('payment.generate');
Route::get('/check-payment-status/{reference}', function ($reference) {
    $controller = app(PaymentController::class);
    return response()->json([
        'status' => $controller->checkPaymentStatus($reference)
    ]);
})->name('payment.check-status')->middleware('web');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');

// ------------------ Custom Documents Routes ------------------
Route::middleware(['auth'])->group(function () {
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'uploadController'])->name('documents.store');
        Route::get('/archive', [DocumentController::class, 'showArchive'])->name('documents.archive');
        Route::get('/released', [DocumentController::class, 'showReleased'])->name('documents.released');
        Route::get('/pending', [DocumentController::class, 'showPending'])->name('documents.pending');
        Route::get('/complete', [DocumentController::class, 'showComplete'])->name('documents.complete');
            Route::resource('documents', DocumentController::class);
            Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search'); // ✅ Add This Line
        Route::post('/documents/workflows', [DocumentController::class, 'storeWorkflow'])->name('documents.workflows.store');
        Route::get('/documents/workflows', [DocumentController::class, 'workflows'])->name('documents.workflows');


        });
        
        Route::delete('/attachments/{id}', [DocumentController::class, 'deleteAttachment'])->name('documents.attachments.destroy');
    });


    //--Companies ---
    Route::middleware(['auth'])->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::get('/companies/{company}/manage', [CompanyController::class, 'manage'])->name('companies.manage');
    Route::put('/companies/{company}/update-logo', [CompanyController::class, 'updateLogo'])->name('companies.updateLogo');
    Route::put('/companies/{company}/update-name', [CompanyController::class, 'updateName'])->name('companies.updateName');
    Route::put('/companies/{company}/update-theme', [CompanyController::class, 'updateTheme'])->name('companies.updateTheme');




    Route::resource('addresses', AddressController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('offices', OfficeController::class);
    

    // Add the missing users.search route
    Route::post('/users/search', [UserController::class, 'search'])->name('users.search');
});

require __DIR__ . '/auth.php';
