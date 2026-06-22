@extends('layouts.dashboard')

@section('title', 'Agent Dashboard')

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Mon espace Agent
    </h1>
    <p class="text-gray-500">
        Création et suivi de mes opérations
    </p>
</div>

{{-- KPI --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-5 rounded shadow border-l-4 border-blue-600">
        <p class="text-gray-500">Mes opérations</p>
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

</div>

{{-- ACTION PRINCIPALE --}}
<div class="mt-8 bg-white p-6 rounded shadow">

    <h3 class="text-lg font-semibold mb-4">
        Actions rapides
    </h3>

    <a href="#"
       class="bg-blue-600 text-white px-5 py-2 rounded inline-block">
        + Créer une opération
    </a>

</div>

{{-- MES DERNIÈRES OPÉRATIONS --}}
<div class="mt-8 bg-white p-6 rounded shadow">

    <h3 class="text-lg font-semibold mb-4">
        Mes dernières opérations
    </h3>

    <p class="text-gray-500">
        Aucune opération enregistrée
    </p>

</div>

@endsection