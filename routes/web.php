<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;

// ==========================================
// PAGE D'ACCUEIL
// ==========================================
Route::get('/', function () {
    if (Auth::user()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// ==========================================
// ROUTES COMMUNES (UTILISATEURS CONNECTÉS)
// ==========================================
Route::middleware(['auth', 'verified', 'force_password_change'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profil
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
// UTILISATEURS (SUPER ADMIN + CONTRÔLE INTERNE)
// ==========================================
Route::middleware(['auth', 'verified', 'force_password_change', 'role:Super Admin|Controle Interne'])->group(function () {

    Route::resource('users', UserController::class);

});

// ==========================================
// LOGS D'AUDIT (SUPER ADMIN)
// ==========================================
Route::middleware(['auth', 'verified', 'force_password_change', 'role:Super Admin'])->group(function () {

    Route::get('/logs', [AuditLogController::class, 'index'])
        ->name('logs.index');

});

// ==========================================
// TRANSFERTS
// ==========================================
Route::middleware(['auth', 'verified', 'force_password_change'])->group(function () {

    // Création / Modification
    Route::middleware(['role:Super Admin|OPS'])->group(function () {

        Route::get('/transfers/create', [TransferController::class, 'create'])
            ->name('transfers.create');

        Route::post('/transfers', [TransferController::class, 'store'])
            ->name('transfers.store');

        Route::get('/transfers/{transfer}/edit', [TransferController::class, 'edit'])
            ->name('transfers.edit');

        Route::put('/transfers/{transfer}', [TransferController::class, 'update'])
            ->name('transfers.update');
    });

    // Consultation
    Route::middleware(['role:Super Admin|Controle Interne|OPS|CCB'])->group(function () {

        Route::get('/transfers', [TransferController::class, 'index'])
            ->name('transfers.index');

        Route::get('/transfers/export', [TransferController::class, 'export'])
            ->name('transfers.export');

        Route::get('/transfers/{transfer}', [TransferController::class, 'show'])
            ->name('transfers.show');
    });

});

// ==========================================
// EXPORTATIONS
// ==========================================
Route::middleware(['auth', 'verified', 'force_password_change'])->group(function () {

    // Création / Modification
    Route::middleware(['role:Super Admin|OPS'])->group(function () {

        Route::get('/exports/create', [ExportController::class, 'create'])
            ->name('exports.create');

        Route::post('/exports', [ExportController::class, 'store'])
            ->name('exports.store');

        Route::get('/exports/{export}/edit', [ExportController::class, 'edit'])
            ->name('exports.edit');

        Route::put('/exports/{export}', [ExportController::class, 'update'])
            ->name('exports.update');

        Route::delete('/exports/{export}', [ExportController::class, 'destroy'])
            ->name('exports.destroy');

        Route::get('/imports/create', [ImportController::class, 'create'])
            ->name('imports.create');

        Route::post('/imports', [ImportController::class, 'store'])
            ->name('imports.store');

        Route::get('/imports/{import}/edit', [ImportController::class, 'edit'])
            ->name('imports.edit');

        Route::put('/imports/{import}', [ImportController::class, 'update'])
            ->name('imports.update');

        Route::delete('/imports/{import}', [ImportController::class, 'destroy'])
            ->name('imports.destroy');
    });

    // Consultation
    Route::middleware(['role:Super Admin|Controle Interne|OPS|CCB'])->group(function () {

        Route::get('/exports', [ExportController::class, 'index'])
            ->name('exports.index');

        Route::get('/exports/export', [ExportController::class, 'export'])
            ->name('exports.export');

        Route::get('/exports/{export}', [ExportController::class, 'show'])
            ->name('exports.show');

        Route::get('/imports', [ImportController::class, 'index'])
            ->name('imports.index');

        Route::get('/imports/export', [ImportController::class, 'export'])
            ->name('imports.export');

        Route::get('/imports/{import}', [ImportController::class, 'show'])
            ->name('imports.show');
    });

});

// ==========================================
// AUTHENTIFICATION
// ==========================================
require __DIR__.'/auth.php';
