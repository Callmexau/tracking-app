@extends('layouts.dashboard')

@section('title', 'Modifier une exportation')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Modifier une exportation</h3>

        <a href="{{ route('exports.index') }}" class="btn btn-secondary">
            Retour
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-body">

            <form action="{{ route('exports.update', $export) }}" method="POST">

                @csrf
                @method('PUT')

                @include('exports.form')

            </form>

        </div>

    </div>

</div>

@endsection
