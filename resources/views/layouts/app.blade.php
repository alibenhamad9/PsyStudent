<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Soutien Psychologique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6; /* Bleu doux */
            --secondary: #00A896; /* Vert d'eau */
            --accent: #F79646;
            --background: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .navbar {
            background: rgba(30, 41, 59, 0.9) !important;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: white !important;
            letter-spacing: -0.5px;
        }

        .navbar-brand i {
            color: #ef4444;
            animation: beat 1.2s infinite alternate;
        }

        @keyframes beat {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        .nav-link {
            color: rgba(255,255,255,0.7) !important;
            font-weight: 500;
            transition: all 0.2s;
            border-radius: 8px;
            padding: 8px 16px !important;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.08);
        }

        .container-main {
            padding: 20px 0;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            border: none;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.35);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary) 0%, #0d9488 100%);
            border: none;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 168, 150, 0.2);
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 168, 150, 0.35);
        }

        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        footer {
            background: #1e293b;
            color: #94a3b8;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #334155;
            font-size: 0.9rem;
        }
    </style>

    @yield('extra-css')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-heart"></i> PsyStudent
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->nom_complet }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield"></i> Administration</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alertes -->
    @if ($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle"></i> Erreur!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Contenu Principal -->
    <div class="container container-main">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 Plateforme de Soutien Psychologique | Groupe: Ali, Rayen, Hassan | 2 LBC-BIS</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra-js')
</body>
</html>