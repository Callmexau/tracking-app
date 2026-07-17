<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Tracking App</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }

        /* Formes géométriques en arrière-plan */
        body::before {
            content: '';
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(0, 84, 143, 0.05), rgba(0, 163, 173, 0.1));
            bottom: -300px;
            left: -250px;
            z-index: 1;
        }

        body::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(0, 163, 173, 0.08), rgba(0, 84, 143, 0.05));
            top: -250px;
            right: -200px;
            z-index: 1;
        }

        .login-card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 24px;
            padding: 45px 40px;
            box-shadow: 0 20px 60px rgba(0, 36, 61, 0.08);
            position: relative;
            z-index: 10;
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* En-tête de la marque */
        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-name {
            font-size: 2.8rem;
            font-weight: 800;
            /* Dégradé stylé aux couleurs Ecobank */
            background: linear-gradient(135deg, #00548f 0%, #00a3ad 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
            line-height: 1.1;
            margin-bottom: 5px;
        }

        .logo-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 5px;
        }

        .logo-subtitle {
            font-size: 0.85rem;
            font-weight: 500;
            color: #9ca3af;
            letter-spacing: 1px;
        }

        /* Champs de formulaire */
        .field-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group {
            background: #fff;
            border-radius: 14px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: #00a3ad;
            box-shadow: 0 0 0 4px rgba(0, 163, 173, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #9ca3af;
            padding-left: 20px;
        }

        .form-control {
            height: 55px;
            border: none;
            background: transparent;
            font-size: 15px;
            font-weight: 500;
            color: #1f2937;
            box-shadow: none !important;
            padding-left: 10px;
        }

        .form-control::placeholder {
            color: #d1d5db;
            font-weight: 400;
        }

        .btn-toggle-pass {
            background: transparent;
            border: none;
            color: #9ca3af;
            padding-right: 20px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .btn-toggle-pass:hover {
            color: #00548f;
        }

        /* Liens et Boutons */
        .forgot-link {
            text-decoration: none;
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #00a3ad;
        }

        .btn-login {
            background: linear-gradient(135deg, #00548f 0%, #00a3ad 100%);
            border: none;
            height: 58px;
            border-radius: 14px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 84, 143, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0, 84, 143, 0.3);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        @media(max-width:576px) {
            .login-card {
                margin: 20px;
                padding: 35px 25px;
                border-radius: 20px;
            }

            .brand-name {
                font-size: 2.4rem;
            }

            .logo-title {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="logo-container">
        <div class="brand-name">Ecobank</div>
        <h1 class="logo-title">Tracking App</h1>
        <div class="logo-subtitle">Bank Operations</div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <label class="field-label">Adresse Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="exemple@ecobank.com"
                    value="{{ old('email') }}"
                    required
                    autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label class="field-label">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    required>
                <button type="button" class="btn-toggle-pass" onclick="togglePassword()">
                    <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-login w-100">
            Se connecter <i class="bi bi-arrow-right ms-2"></i>
        </button>

    </form>

</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>

</body>
</html>
