<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Tracking App') - ECG</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="page">
    
    {{-- SIDEBAR NATIVE TABLER --}}
    <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <h1 class="navbar-brand navbar-brand-autodark px-3">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                    <div class="font-weight-bold text-white fs-2">Tracking App</div>
                    <div class="small text-muted fs-5 fw-normal">Division Transferts</div>
                </a>
            </h1>

            <div class="collapse navbar-collapse" id="sidebar-menu">
                <ul class="navbar-nav pt-lg-3">
                    
                    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                            </span>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>

                    @if(auth()->user()->hasAnyRole(['Super Admin','Admin']))
                    <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            </span>
                            <span class="nav-link-title">Utilisateurs</span>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M7 15v-4a2 2 0 0 1 4 0v4" /><path d="M7 13h4" /><path d="M14 11v4h3" /></svg>
                            </span>
                            <span class="nav-link-title">Logs Système</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8l4 4m0 0l-4 4m4 -4h-14" /><path d="M12 5l0 14" /></svg>
                            </span>
                            <span class="nav-link-title">Transferts</span>
                        </a>
                    </li>

                </ul>

                <div class="mt-auto mb-3 px-3 w-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3m0 6l3 -3" /></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- PAGE WRAPPER --}}
    <div class="page-wrapper">
        
        {{-- TOPBAR NATIVE --}}
        <header class="navbar navbar-expand-md navbar-light d-none d-lg-flex d-print-none">
            <div class="container-xl">
                <div class="navbar-nav flex-row order-md-last ms-auto">
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0">
                            <span class="avatar avatar-sm bg-blue text-white fw-bold">
                                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                                <div class="mt-1 small text-muted">{{ auth()->user()->getRoleNames()->first() }}</div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="page-body">
            <div class="container-xl">
                
                @if(session('success'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>{{ session('success') }}</div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>
</div>

</body>
</html>