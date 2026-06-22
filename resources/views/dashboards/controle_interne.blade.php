@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Tableau de bord Administrateur
    </h1>
    <p class="text-gray-500">
        Supervision et validation des opérations bancaires
    </p>
</div>

{{-- KPI --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <div class="bg-white p-5 rounded shadow border-l-4 border-blue-600">
        <p class="text-gray-500">Opérations total</p>
        <h2 class="text-3xl font-bold">0</h2>
    </div>

    <div class="bg-white p-5 rounded shadow border-l-4 border-yellow-500">
        <p class="text-gray-500">En attente</p>
        <h2 class="text-3xl font-bold">0</h2>
    </div>

    <div class="bg-white p-5 rounded shadow border-l-4 border-green-600">
        <p class="text-gray-500">Validées</p>
        <h2 class="text-3xl font-bold">0</h2>
    </div>

    <div class="bg-white p-5 rounded shadow border-l-4 border-red-500">
        <p class="text-gray-500">Rejetées</p>
        <h2 class="text-3xl font-bold">0</h2>
    </div>

</div>

{{-- SECTION OPÉRATIONS --}}
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- OPÉRATIONS EN ATTENTE --}}
    <div class="bg-white p-5 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">
            Opérations en attente
        </h3>

        <p class="text-gray-500">
            Aucune opération à valider
        </p>
    </div>

    {{-- ACTIVITÉ AGENTS --}}
    <div class="bg-white p-5 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">
            Activité des agents
        </h3>

        <p class="text-gray-500">
            Aucune activité récente
        </p>
    </div>

</div>

{{-- ACTIONS RAPIDES --}}
<div class="mt-8 bg-white p-5 rounded shadow">

    <h3 class="text-lg font-semibold mb-4">
        Actions rapides
    </h3>

    <div class="flex gap-4">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Voir opérations
        </button>

        <button class="bg-yellow-500 text-white px-4 py-2 rounded">
            Export Excel
        </button>

    </div>

</div>

@endsection