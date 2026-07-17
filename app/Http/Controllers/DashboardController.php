<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Import;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $period = $request->query('period', '7d');

        // Super Admin Dashboard (Accès complet avec graphiques, logs et gestion de profils)
        if ($user->hasRole('Super Admin')) {
            $data = array_merge($this->getCommonDashboardData(true, $period), [
                'showChart'         => true,
                'canManageProfiles' => true,
                'canViewLogs'       => true,
                'chartPeriod'       => $period,
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

    private function getCommonDashboardData(bool $includeChart = true, string $period = '7d'): array
    {
        $chartData = [
            'labels' => [],
            'data' => [],
            'exportData' => [],
        ];

        $roleDistribution = Role::withCount('users')->get();
        $roleLabels = $roleDistribution->pluck('name')->toArray();
        $roleCounts = $roleDistribution->pluck('users_count')->toArray();

        if ($includeChart && class_exists(Transfer::class)) {
        $days = match ($period) {
            '30d' => 29,
            'year' => now()->dayOfYear - 1,
            default => 6,
        };

        $startDate = now()->subDays($days)->startOfDay();

        $transfersPerDate = Transfer::select(
            DB::raw('DATE(date_depot) as date'),
            DB::raw('count(*) as total')
        )
        ->where('date_depot', '>=', $startDate)
        ->groupBy('date')
        ->pluck('total', 'date')
        ->toArray();

        $exportsPerDate = DB::table('exports')
            ->select(DB::raw('DATE(date_domiciliation) as date'), DB::raw('count(*) as total'))
            ->where('date_domiciliation', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $importsPerDate = DB::table('imports')
            ->select(DB::raw('DATE(date_domiciliation) as date'), DB::raw('count(*) as total'))
            ->where('date_domiciliation', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $data = [];
        $exportData = [];
        $importData = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $data[] = $transfersPerDate[$dateString] ?? 0;
            $exportData[] = $exportsPerDate[$dateString] ?? 0;
            $importData[] = $importsPerDate[$dateString] ?? 0;
        }

        $chartData = [
            'labels' => $labels,
            'data' => $data,
            'exportData' => $exportData,
            'importData' => $importData,
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
        'roleLabels' => $roleLabels,
        'roleCounts' => $roleCounts,
    ];
}
}
