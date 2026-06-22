<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Infos utilisateur -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">

                <h3 class="text-2xl font-bold">
                    Bienvenue, {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                </h3>

                <p class="mt-2 text-gray-600">
                    Rôle :
                    <span class="font-semibold text-blue-600">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </span>
                </p>

            </div>

            <!-- DASHBOARD ADMIN -->
            @role('Super Admin|Admin')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h5 class="text-gray-500 text-sm">Utilisateurs</h5>
                    <p class="text-3xl font-bold text-purple-600">1</p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h5 class="text-gray-500 text-sm">Opérations Totales</h5>
                    <p class="text-3xl font-bold text-blue-600">0</p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h5 class="text-gray-500 text-sm">En attente</h5>
                    <p class="text-3xl font-bold text-yellow-500">0</p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h5 class="text-gray-500 text-sm">Validées</h5>
                    <p class="text-3xl font-bold text-green-600">0</p>
                </div>

            </div>
            @endrole

            <!-- DASHBOARD AGENT -->
            @role('Agent')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h5 class="text-gray-500 text-sm">Mes opérations</h5>
                    <p class="text-3xl font-bold text-blue-600">0</p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h5 class="text-gray-500 text-sm">En attente</h5>
                    <p class="text-3xl font-bold text-yellow-500">0</p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h5 class="text-gray-500 text-sm">Validées</h5>
                    <p class="text-3xl font-bold text-green-600">0</p>
                </div>

            </div>
            @endrole

        </div>
    </div>
</x-app-layout>