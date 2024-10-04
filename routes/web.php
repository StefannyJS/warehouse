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


// Authentication routes
require __DIR__.'/auth.php';
