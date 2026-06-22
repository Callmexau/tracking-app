@extends('layouts.dashboard')

@section('title', 'Logs Système')

@section('content')
<div class="container-fluid p-0 animate__animated animate__fadeIn">
    <div class="mb-4">
        <h1 class="h3 text-dark fw-bold mb-1">Logs Système</h1>
        <p class="text-muted small mb-0">Visualisez l'historique des actions effectuées.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted" style="font-size: 12px;">
                        <tr>
                            <th class="ps-4 py-3">Date</th>
                            <th class="py-3">Heure</th>
                            <th class="py-3">Auteur</th>
                            <th class="py-3">Action</th>
                            <th class="pe-4 py-3 w-50">Description</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="ps-4 py-2 text-muted text-nowrap">{{ $log->created_at->format('d/m/Y') }}</td>
                                <td class="py-2 text-dark fw-medium text-nowrap">{{ $log->created_at->format('H:i') }}</td>
                                <td class="py-2 fw-semibold text-dark text-nowrap">
                                    {{ $log->user->first_name ?? 'Système' }} {{ $log->user->last_name ?? '' }}
                                </td>
                                <td class="py-2">
                                    <span class="badge bg-light text-dark border">{{ $log->action }}</span>
                                </td>
                                <td class="pe-4 py-2 text-muted">{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Aucun log enregistré pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection