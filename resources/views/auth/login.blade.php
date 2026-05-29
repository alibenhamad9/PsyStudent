@extends('layouts.app')

@section('title', 'Connexion')

@section('extra-css')
<style>
    .auth-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-top: 30px;
        transition: all 0.3s;
    }

    .auth-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }

    .auth-header {
        background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
        color: white;
        padding: 35px 20px;
        text-align: center;
        position: relative;
    }

    .auth-header i {
        font-size: 2.2rem;
        margin-bottom: 12px;
        animation: float 3s ease-in-out infinite alternate;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        100% { transform: translateY(-8px); }
    }

    .auth-body {
        padding: 40px;
    }

    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .input-group-custom {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-group-custom i {
        position: absolute;
        left: 15px;
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
    }

    .input-group-custom .form-control {
        padding-left: 45px;
        border-radius: 10px;
        background: #f8fafc;
    }

    .btn-auth {
        padding: 12px;
        font-weight: 700;
        border-radius: 50px;
        margin-top: 15px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .test-credentials {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 15px;
        color: #166534;
        margin-top: 25px;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="auth-card card">
            <div class="auth-header">
                <i class="fas fa-heartbeat"></i>
                <h3 class="fw-bold mb-1">Ravi de vous revoir !</h3>
                <p class="mb-0 text-white-50 small">Connectez-vous à votre espace serein de soutien</p>
            </div>

            <div class="auth-body">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <div class="input-group-custom">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required placeholder="exemple@etudiant.fr">
                        </div>
                        @error('email')
                            <span class="invalid-feedback d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required placeholder="••••••••">
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-auth shadow-lg">
                        Se connecter <i class="fas fa-sign-in-alt ms-1"></i>
                    </button>
                </form>

                <hr class="my-4" style="border-color: #e2e8f0;">

                <p class="text-center text-muted mb-0 small">
                    Pas encore inscrit ? <a href="{{ route('register') }}" class="fw-bold text-primary">Créer un compte</a>
                </p>

                <!-- Compte de test élégant -->
                <div class="test-credentials text-start">
                    <strong class="d-block mb-1"><i class="fas fa-info-circle"></i> Compte de test :</strong>
                    <div class="d-flex justify-content-between">
                        <span>Email: <code>admin@example.com</code></span>
                        <span>MDP: <code>password123</code></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection