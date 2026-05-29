<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - @yield('title') - PsyStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1; /* Indigo */
            --primary-dark: #4f46e5;
            --accent: #10b981; /* Emeraude */
            --danger: #ef4444;
            --background: #f1f5f9;
            --dark-sidebar: #0f172a; /* Slate 900 */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', 'Segoe UI', sans-serif;
            background: var(--background);
            color: #1e293b;
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            align-items: stretch;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: var(--dark-sidebar);
            color: #f8fafc;
            transition: all 0.3s;
            min-height: 100vh;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: #020617;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        #sidebar .sidebar-header h3 {
            font-weight: 800;
            font-size: 1.4rem;
            margin-bottom: 0;
            color: #fff;
            letter-spacing: -0.5px;
        }

        #sidebar .sidebar-header h3 i {
            color: var(--primary);
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li {
            padding: 5px 15px;
        }

        #sidebar ul li a {
            padding: 12px 15px;
            font-size: 1rem;
            display: block;
            color: #94a3b8;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        #sidebar ul li.active > a {
            color: #fff;
            background: var(--primary);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        #sidebar ul li a i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Page Content */
        #content {
            width: 100%;
            padding: 30px;
            transition: all 0.3s;
        }

        .admin-navbar {
            background: #ffffff;
            padding: 15px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            margin-bottom: 30px;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
        }

        .card {
            border: 1px solid rgba(0,0,0,0.04);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-stat {
            border-left: 5px solid var(--primary);
        }

        .alert-dismissible .btn-close {
            padding: 1.25rem;
        }
    </style>
    @yield('extra-css')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-user-shield me-2"></i>PsyStudent <span class="badge bg-primary fs-7" style="font-size: 0.7rem; vertical-align: middle;">ADMIN</span></h3>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-pie"></i>Dashboard</a>
                </li>
                <li class="{{ Request::routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i>Étudiants</a>
                </li>
                <li class="{{ Request::routeIs('admin.appointments.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.appointments.index') }}"><i class="fas fa-calendar-alt"></i>Rendez-vous</a>
                </li>
                <li class="{{ Request::routeIs('admin.resources.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.resources.index') }}"><i class="fas fa-book-medical"></i>Ressources</a>
                </li>
                <li>
                    <a href="/"><i class="fas fa-home"></i>Retour au site</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar admin-navbar">
                <div class="container-fluid p-0">
                    <span class="navbar-text fw-bold text-dark fs-5">
                        Espace Administration
                    </span>
                    <div class="d-flex align-items-center">
                        <span class="me-3 text-muted"><i class="fas fa-user-circle me-1"></i>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Alerts -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-circle me-2"></i>Erreur!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Main Content Area -->
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra-js')
</body>
</html>
