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

        // Redirection pour les utilisateurs avec le rôle Ops
        if ($user->hasRole('OPS')) {
            return view('dashboards.ops');
        }

        // Redirection pour les utilisateurs avec le rôle CCB
        if ($user->hasRole('CCB')) {
            return view('dashboards.ccb');
        }

        abort(403);
    }

    private function getCommonDashboardData(bool $includeChart = true): array
    {
        $chartData = [];
        
        if ($includeChart && class_exists(Transfer::class)) {
            $transfersPerDate = Transfer::select(
                DB::raw('DATE(date_depot) as date'),
                DB::raw('count(*) as total')
            )
            ->where('date_depot', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('total', 'date');

            $chartData = [
                'labels' => $transfersPerDate->keys()->toArray(),
                'data' => $transfersPerDate->values()->toArray()
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