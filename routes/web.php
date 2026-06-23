<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\TransferController;

// Route page d'accueil-connexion
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Routes protégées de base (Communes à tous les connectés : Profil)
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Routes du profil (Breeze par défaut)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
});

// ==========================================
// ACCÈS RÉSERVÉ : SUPER ADMIN & CONTRÔLE INTERNE
// ==========================================
Route::middleware(['auth', 'verified', 'role:Super Admin|Controle Interne'])->group(function () {
    // Gestion des utilisateurs 
    Route::resource('users', UserController::class);
});

// ==========================================
// ACCÈS RÉSERVÉ : SUPER ADMIN UNIQUEMENT
// ==========================================
Route::middleware(['auth', 'verified', 'role:Super Admin'])->group(function () {
    // Gestion des logs d'audit
    Route::get('/logs', [AuditLogController::class, 'index'])->name('logs.index');
});

// Accès aux transferts : Consultation, Export, Création et Affichage détaillé
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Consultation Index et Export : Accessibles à TOUS les rôles (Super Admin, CI, OPS, CCB)
    Route::middleware(['role:Super Admin|Controle Interne|OPS|CCB'])->group(function () {
        Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
        Route::get('/transfers/export', [TransferController::class, 'export'])->name('transfers.export');
    });

    // Création / Enregistrement : Réservé uniquement au Super Admin et aux OPS
    Route::middleware(['role:Super Admin|OPS'])->group(function () {
        Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
        Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');
    });

    // Route dynamique : Placée en dernier pour ne pas intercepter /create ou /export
    Route::middleware(['role:Super Admin|Controle Interne|OPS|CCB'])->group(function () {
        Route::get('/transfers/{transfer}', [TransferController::class, 'show'])->name('transfers.show');
    });

});

// Auth routes
require __DIR__.'/auth.php';