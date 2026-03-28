<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\WorkshopController as AdminWorkshopController;
use App\Http\Controllers\Employee\WorkshopController as EmployeeWorkshopController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Rotte Web
|--------------------------------------------------------------------------
|
| Le rotte sono suddivise per ruolo: admin ed employee hanno prefissi
| e middleware separati. La rotta /dashboard fa da "smistatore" e
| redirige alla sezione corretta in base al ruolo dell'utente.
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect intelligente post-login: in base al ruolo porta alla sezione giusta
Route::get('/dashboard', function () {
    return auth()->user()->role === UserRole::Admin
        ? redirect()->route('admin.dashboard')
        : redirect()->route('employee.workshops.index');
})->middleware('auth')->name('dashboard');

// ── Admin ────────────────────────────────────────────────────────────────
// CRUD workshop + dashboard statistiche + endpoint live per polling
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('workshops', AdminWorkshopController::class);
    Route::get('dashboard', [StatsController::class, 'index'])->name('dashboard');
    Route::get('stats/live', [StatsController::class, 'live'])->name('stats.live');
});

// ── Employee ─────────────────────────────────────────────────────────────
// Lista workshop, dettaglio, iscrizione e cancellazione
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('workshops', [EmployeeWorkshopController::class, 'index'])->name('workshops.index');
    Route::get('workshops/{workshop}', [EmployeeWorkshopController::class, 'show'])->name('workshops.show');
    Route::post('workshops/{workshop}/register', [EmployeeWorkshopController::class, 'register'])->name('workshops.register');
    Route::delete('registrations/{registration}', [EmployeeWorkshopController::class, 'cancelRegistration'])->name('registrations.destroy');
});

// ── Profilo (condiviso tra admin e employee) ─────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
