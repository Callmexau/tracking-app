<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Super Admin Dashboard (Accès complet avec graphiques, logs et gestion de profils)
        if ($user->hasRole('Super Admin')) {
            $data = array_merge($this->getCommonDashboardData(true), [
                'showChart'         => true,
                'canManageProfiles' => true,
                'canViewLogs'       => true,
            ]);
            return view('dashboards.superadmin', $data);
        }

        // Contrôle Interne (Redirigé vers la vue users.index avec la variable $users injectée)
        if ($user->hasRole('Controle Interne')) {
            $data = array_merge($this->getCommonDashboardData(false), [
                'showChart'         => false,
                'canManageProfiles' => false,
                'canViewLogs'       => false,
                // On injecte les utilisateurs filtrés (OPS et CCB) comme attendu par users.index
                'users'             => User::role(['OPS', 'CCB'])->latest()->paginate(10),
            ]);
            
            return view('users.index', $data);
        }

        // Redirection directe vers les transferts pour le rôle OPS
        if ($user->hasRole('OPS')) {
            return redirect()->route('transfers.index');
        }

        // Redirection directe vers les transferts pour le rôle CCB
        if ($user->hasRole('CCB')) {
            return redirect()->route('transfers.index');
        }

        abort(403);
    }

    private function getCommonDashboardData(bool $includeChart = true): array
{
    $chartData = [
        'labels' => [],
        'data' => []
    ];
    
    if ($includeChart && class_exists(Transfer::class)) {
        // 1. On récupère les données de la DB (sur le début de la journée startOfDay pour ne rien rater)
        $transfersPerDate = Transfer::select(
            DB::raw('DATE(date_depot) as date'),
            DB::raw('count(*) as total')
        )
        ->where('date_depot', '>=', now()->subDays(6)->startOfDay()) // 6 jours + aujourd'hui = 7 jours
        ->groupBy('date')
        ->pluck('total', 'date')
        ->toArray(); // On convertit en tableau PHP standard

        // 2. On génère TOUS les 7 derniers jours pour être sûr de ne pas avoir de trou
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            // Format Y-m-d pour correspondre à la clé de la DB (ex: 2026-06-26)
            $dateString = now()->subDays($i)->format('Y-m-d');
            
            // Format d'affichage sur le graphique (ex: 26/06)
            $labels[] = now()->subDays($i)->format('d/m'); 
            
            // Si la date existe en DB on prend le total, sinon on met 0
            $data[] = $transfersPerDate[$dateString] ?? 0;
        }

        $chartData = [
            'labels' => $labels,
            'data' => $data
        ];
    }

    return [
        'totalUsers'           => User::count(),
        'totalControleInterne' => User::role('Controle Interne')->count(),
        'totalOps'             => User::role('OPS')->count(),
        'totalCCB'             => User::role('CCB')->count(),
        'activeUsers'          => User::where('is_active', true)->count(),
        
        'recentUsers' => User::latest()->take(5)->get(),

        'totalTransfers'     => class_exists(Transfer::class) ? Transfer::count() : 0,
        'pendingTransfers'   => class_exists(Transfer::class) ? Transfer::where('statut', 'Non traité')->count() : 0,
        'processedTransfers' => class_exists(Transfer::class) ? Transfer::where('statut', 'Traité')->count() : 0,
        'rejectedTransfers'  => class_exists(Transfer::class) ? Transfer::where('statut', 'Rejet')->count() : 0,
        
        'chartData' => $chartData,
    ];
}
}