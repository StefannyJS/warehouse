<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\UserRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StorageController;
use App\http\controllers\CellController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\StockConversionController;
use App\Http\Controllers\AdjustmentController;

// Login route
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

// Redirect users based on their role when accessing the root URL
Route::get('/', function () {
    return Auth::user()->hasRole('super-admin') ? 
           redirect()->route('superadmin.dashboard') : 
           redirect()->route('dashboard');
})->middleware('auth');

// User dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile management routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super-admin routes for user registration
Route::middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/superadmin/register-user', [UserRegistrationController::class, 'create'])->name('superadmin.register-user.create');
    Route::post('/superadmin/register-user', [UserRegistrationController::class, 'store'])->name('superadmin.register-user.store');
    Route::get('superadmin/dashboard', [HomeController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/laratrust', function () {
        return view('laratrust.index');
    })->name('laratrust.index');
});

// routes for materials
Route::middleware(['auth'])->group(function () {
    Route::get('/materials/data', [MaterialController::class, 'data'])->name('materials.data');
    Route::resource('/materials', MaterialController::class);
    Route::get('/materials/active', [MaterialController::class, 'getActiveMaterials'])->name('materials.active');
});

// Routes for UOMs
Route::middleware(['auth'])->group(function () {
    Route::resource('/uoms', UomController::class)->except(['show']);
    Route::get('/uoms/data', [UomController::class, 'data'])->name('uoms.data');
});

// Routes for department
Route::middleware(['auth'])->group(function () {
    Route::get('/departments/data', [DepartmentController::class, 'getData'])->name('departments.data');
    Route::resource('/departments', DepartmentController::class);
});

// Routes for storage
Route::middleware(['auth'])->group(function () {
    Route::get('/storages/data', [StorageController::class, 'getData'])->name('storages.data');
    Route::resource('/storages', StorageController::class);
});

// Routes for cell
Route::middleware(['auth'])->group(function () {
    Route::prefix('storages/{storage_id}')->group(function () {
        Route::resource('cells', CellController::class)->except(['show']);
        Route::get('cells/data', [CellController::class, 'getData'])->name('cells.data');

        // Update cell
        Route::put('cells/{cell}', [CellController::class, 'update'])->name('cells.update');
        Route::delete('cells/{cell}', [CellController::class, 'destroy'])->name('cells.destroy');
    });
});

// Routes for Stock in
Route::middleware(['auth'])->group(function () {
    Route::get('/stock-in/data', [StockInController::class, 'data'])->name('stock-in.data');
    Route::resource('stock-in', StockInController::class)->except(['show']);

});

// Routes for stock in no.
Route::get('/stock-in/generate-number', [StockInController::class, 'generateStockInNoApi'])->name('stock-in.generate-number');


// Routes for stock out
Route::resource('stock-out', StockOutController::class);


// Routes for stock transfer
Route::resource('stock-transfer', StockTransferController::class);


// Routes for stock conversion
Route::resource('stock-conversion', StockConversionController::class);


// Routes for adjustment
Route::resource('adjustment', AdjustmentController::class);


// Authentication routes
require __DIR__.'/auth.php';
