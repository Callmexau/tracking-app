@extends('layouts.dashboard')

@section('title', 'Modifier importation')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Modifier l'importation</h3>
            <p class="text-muted small mb-0">Mettez à jour les informations de l'importation.</p>
        </div>
        <a href="{{ route('imports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('imports.update', $import) }}" method="POST">
                @csrf
                @method('PUT')

                @include('imports.form')

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">
                        Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
