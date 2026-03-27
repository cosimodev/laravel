<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\WorkshopController as AdminWorkshopController;
use App\Http\Controllers\Employee\WorkshopController as EmployeeWorkshopController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    return auth()->user()->role === UserRole::Admin
        ? redirect()->route('admin.dashboard')
        : redirect()->route('employee.workshops.index');
})->middleware('auth')->name('dashboard');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('workshops', AdminWorkshopController::class);
    Route::get('dashboard', [StatsController::class, 'index'])->name('dashboard');
    Route::get('stats/live', [StatsController::class, 'live'])->name('stats.live');
});

// Employee routes
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('workshops', [EmployeeWorkshopController::class, 'index'])->name('workshops.index');
    Route::get('workshops/{workshop}', [EmployeeWorkshopController::class, 'show'])->name('workshops.show');
    Route::post('workshops/{workshop}/register', [EmployeeWorkshopController::class, 'register'])->name('workshops.register');
    Route::delete('registrations/{registration}', [EmployeeWorkshopController::class, 'cancelRegistration'])->name('registrations.destroy');
});

// Profile routes (shared)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
