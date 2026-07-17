<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Tracking App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #00548f;
            --brand-secondary: #00a3ad;
            --sidebar-bg: #0b1320;
            --sidebar-active: rgba(0, 163, 173, 0.15);
            --body-bg: #f4f7f6;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 75px;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg);
            color: #374151;
            overflow: hidden;
        }

        /* WRAPPER STRUCTURE FIXED TO SCREEN */
        .wrapper {
            display: flex;
            align-items: stretch;
            width: 100vw;
            height: 100vh;
        }

        /* SIDEBAR STYLE FIXED WITH TRANSITION */
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
            z-index: 99;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Quand la sidebar est rétractée */
        #sidebar.collapsed {
            min-width: var(--sidebar-collapsed-width);
            max-width: var(--sidebar-collapsed-width);
        }

        /* HEADER SIDEBAR AVEC POSITION RELATIVE POUR CENTRAGE PARFAIT */
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center; /* Centre le contenu par défaut */
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        #sidebar.collapsed .sidebar-header {
            padding: 24px 0;
        }

        /* Masquage fluide des blocs de textes en mode rétracté */
        #sidebar.collapsed .sidebar-brand-wrapper,
        #sidebar.collapsed .sidebar-link span,
        #sidebar.collapsed .btn-logout span {
            display: none !important;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff 30%, var(--brand-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }

        .sidebar-tagline {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6b7280;
            font-weight: 500;
        }

        /* Bouton Hamburger isolé sur la droite sans décaler le titre */
        .btn-toggle-sidebar {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            font-size: 18px;
            color: #9ca3af;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            position: absolute;
            right: 15px; /* Aligné à droite */
        }

        #sidebar.collapsed .btn-toggle-sidebar {
            position: static; /* Se recentre quand le titre disparaît */
        }

        .btn-toggle-sidebar:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Ajustement de la navigation */
        .sidebar-nav {
            padding: 15px 10px;
            list-style: none;
            margin-bottom: auto;
        }

        .sidebar-item {
            margin-bottom: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .sidebar-link i {
            font-size: 16px;
            margin-right: 12px;
            transition: transform 0.25s ease;
        }

        /* Centrage parfait des icônes seules en mode replié */
        #sidebar.collapsed .sidebar-link i {
            margin-right: 0;
            font-size: 18px;
            width: 100%;
            text-align: center;
        }

        .sidebar-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.03);
        }

        .sidebar-link:hover i {
            transform: translateX(3px);
        }

        #sidebar.collapsed .sidebar-link:hover i {
            transform: scale(1.1);
        }

        .sidebar-item.active .sidebar-link {
            color: #fff;
            background: linear-gradient(135deg, rgba(0, 84, 143, 0.4) 0%, var(--sidebar-active) 100%);
            border-left: 4px solid var(--brand-secondary);
            font-weight: 600;
        }

        .sidebar-item.active .sidebar-link i {
            color: var(--brand-secondary);
        }

        /* COMPOSANT DECONNEXION REPENSI EN VRAI BOUTON ROUGE */
        .sidebar-footer {
            padding: 15px 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(0, 0, 0, 0.15);
            transition: padding 0.3s;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 11px 15px;
            color: #ffffff; /* Texte blanc pour un vrai bouton */
            background-color: #dc2626; /* Rouge solide */
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-logout i {
            font-size: 16px;
            margin-right: 10px;
        }

        /* Comportement en mode rétracté */
        #sidebar.collapsed .btn-logout {
            padding: 11px 0;
            background-color: transparent;
            box-shadow: none;
            color: #ef4444;
        }

        #sidebar.collapsed .btn-logout i {
            margin-right: 0;
            font-size: 18px;
        }

        .btn-logout:hover {
            background-color: #b91c1c; /* Rouge plus sombre au survol */
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(220, 38, 38, 0.3);
        }

        #sidebar.collapsed .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            transform: none;
            box-shadow: none;
        }

        /* MAIN CONTENT AREA */
        #content {
            width: 100%;
            padding: 24px 30px;
            height: 100vh;
            overflow-y: auto;
            box-sizing: border-box;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* NAVBAR TOP PROFILE */
        .top-profile {
            background: #fff;
            padding: 12px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            margin-bottom: 24px;
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        @media (max-width: 992px) {
            html, body { overflow: auto; }
            .wrapper { flex-direction: column; height: auto; }
            #sidebar { min-width: 100%; max-width: 100%; height: auto; }
            #sidebar.collapsed { min-width: 100%; max-width: 100%; }
            #content { padding: 15px; height: auto; overflow-y: visible; }
            .btn-toggle-sidebar { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="wrapper">

    <nav id="sidebar">
        <div>
            <div class="sidebar-header">
                <div class="sidebar-brand-wrapper">
                    <div class="sidebar-brand">Ecobank</div>
                    <div class="sidebar-tagline">Tracking App</div>
                </div>
                <button class="btn-toggle-sidebar" id="toggleSidebarBtn">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <ul class="sidebar-nav">
                {{-- Réservé exclusivement au Super Admin --}}
                @role('Super Admin')
                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="sidebar-link">
                        <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
                    </a>
                </li>
                @endrole

                {{-- Accès restreint : Uniquement Super Admin et Contrôle Interne --}}
                @hasanyrole(['Super Admin', 'Controle Interne'])
                <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="sidebar-link">
                        <i class="bi bi-people"></i> <span>Utilisateurs</span>
                    </a>
                </li>
                @endhasanyrole

                {{-- Accès ultra-restreint : Réservé exclusivement au Super Admin --}}
                @role('Super Admin')
                <li class="sidebar-item {{ request()->routeIs('logs.index') ? 'active' : '' }}">
                    <a href="{{ route('logs.index') }}" class="sidebar-link">
                        <i class="bi bi-terminal"></i> <span>Logs Système</span>
                    </a>
                </li>
                @endrole

                {{-- Transferts : Visibles uniquement par Super Admin, OPS et CCB (Contrôle Interne exclu) --}}
                @hasanyrole(['Super Admin', 'OPS', 'CCB'])
                <li class="sidebar-item {{ request()->routeIs('transfers.*') ? 'active' : '' }}">
                    <a href="{{ route('transfers.index') }}" class="sidebar-link">
                        <i class="bi bi-arrow-left-right"></i> <span>Transferts</span>
                    </a>
                </li>
                @endhasanyrole


                {{-- Exportations : Visibles uniquement par Super Admin, OPS et CCB (Contrôle Interne exclu) --}}
                @hasanyrole(['Super Admin', 'OPS', 'CCB'])
                <li class="sidebar-item {{ request()->routeIs('exports.*') ? 'active' : '' }}">
                    <a href="{{ route('exports.index') }}" class="sidebar-link">
                        <i class="bi bi-box-arrow-up-right"></i> <span>Exportations</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('imports.*') ? 'active' : '' }}">
                    <a href="{{ route('imports.index') }}" class="sidebar-link">
                        <i class="bi bi-box-arrow-down-left"></i> <span>Importations</span>
                    </a>
                </li>
                @endhasanyrole

                {{-- Visible par tous les profils authentifiés --}}
                <li class="sidebar-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <i class="bi bi-person-circle"></i> <span>Mon Profil</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </nav>

    <main id="content">

        <div class="top-profile d-flex justify-content-between align-items-center">
            <div class="text-muted small fw-medium">
                <i class="bi bi-calendar3 me-2"></i> {{ now()->translatedFormat('l d F Y') }}
            </div>

            <div class="d-flex align-items-center">
                <div class="text-end me-3 d-none d-sm-block">
                    <div class="fw-semibold text-dark mb-0" style="font-size: 14px;">{{ auth()->user()->first_name ?? 'Claude' }} {{ auth()->user()->last_name ?? 'Yoka' }}</div>
                    <div class="text-muted small" style="font-size: 11px;">{{ auth()->user()->roles->first()->name ?? 'Super Admin' }}</div>
                </div>
                <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center text-uppercase">
                    {{ substr(auth()->user()->first_name ?? 'C', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'Y', 0, 1) }}
                </div>
            </div>
        </div>

        @yield('content')

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebarBtn');

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });

        const sessionTimeoutMs = {{ config('session.lifetime') * 60 * 1000 }};
        const warningDurationMs = 2 * 60 * 1000; // warning 2 minutes before expiration
        const warningDelayMs = Math.max(sessionTimeoutMs - warningDurationMs, 0);
        const logoutRedirect = '{{ route('login') }}';
        const modalElement = document.getElementById('sessionTimeoutModal');
        const countdownElement = document.getElementById('sessionTimeoutCountdown');
        const stayButton = document.getElementById('stayLoggedInButton');
        const sessionModal = modalElement ? new bootstrap.Modal(modalElement) : null;

        let warningTimer;
        let logoutTimer;
        let countdownTimer;

        function clearSessionTimers() {
            clearTimeout(warningTimer);
            clearTimeout(logoutTimer);
            clearInterval(countdownTimer);
        }

        function startSessionTimers() {
            clearSessionTimers();

            warningTimer = setTimeout(showSessionWarning, warningDelayMs);
            logoutTimer = setTimeout(expireSession, sessionTimeoutMs);
        }

        function expireSession() {
            if (sessionModal) {
                sessionModal.hide();
            }

            const logoutForm = document.getElementById('sessionLogoutForm');

            if (logoutForm) {
                logoutForm.submit();
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('logout') }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ auto_logout: true }),
            })
            .then((response) => {
                if (!response.ok) {
                    window.location.href = logoutRedirect;
                }
            })
            .catch(() => {
                window.location.href = logoutRedirect;
            });
        }

        function showSessionWarning() {
            if (!sessionModal) {
                return;
            }

            let remainingSeconds = Math.ceil(warningDurationMs / 1000);
            countdownElement.textContent = remainingSeconds;
            sessionModal.show();

            countdownTimer = setInterval(() => {
                remainingSeconds -= 1;
                countdownElement.textContent = remainingSeconds;
            }, 1000);
        }

        function resetSessionTimers() {
            startSessionTimers();
            if (sessionModal) {
                sessionModal.hide();
            }
        }

        ['click', 'keypress', 'scroll', 'mousemove', 'touchstart'].forEach((event) => {
            document.addEventListener(event, resetSessionTimers, { passive: true });
        });

        if (stayButton) {
            stayButton.addEventListener('click', resetSessionTimers);
        }

        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', resetSessionTimers);
        }

        startSessionTimers();
    });
</script>

<div class="modal fade" id="sessionTimeoutModal" tabindex="-1" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionTimeoutModalLabel">Session inactive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Votre session expirera dans <strong><span id="sessionTimeoutCountdown">120</span></strong> secondes.
                Cliquez sur <strong>Rester connecté</strong> pour continuer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="stayLoggedInButton" data-bs-dismiss="modal">Rester connecté</button>
            </div>
        </div>
    </div>
</div>

    <form id="sessionLogoutForm" method="POST" action="{{ route('logout') }}" class="d-none">
        @csrf
        <input type="hidden" name="auto_logout" value="1">
    </form>

@stack('scripts')
</body>
</html>
