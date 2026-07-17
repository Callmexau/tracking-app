<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller implements HasMiddleware
{
    // Définition moderne du middleware d'authentification
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function index()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Sécurité : Seul le Super Admin ou le Contrôle Interne peuvent auditer les logs
        if (!$currentUser->hasRole('Super Admin') && !$currentUser->hasRole('Controle Interne')) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupération des 20 derniers logs par page, triés par les plus récents
        $logs = AuditLog::with('user')->latest()->paginate(25);

        return view('logs.index', compact('logs'));
    }
}
