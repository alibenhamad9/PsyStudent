@extends('layouts.app')

@section('title', 'Créer un compte')

@section('extra-css')
<style>
    .auth-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-top: 20px;
        transition: all 0.3s;
    }

    .auth-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }

    .auth-header {
        background: linear-gradient(135deg, var(--secondary) 0%, #0d9488 100%);
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
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="auth-card card">
            <div class="auth-header">
                <i class="fas fa-user-plus"></i>
                <h3 class="fw-bold mb-1">Rejoignez-nous !</h3>
                <p class="mb-0 text-white-50 small">Créez votre profil et commencez votre suivi bien-être</p>
            </div>

            <div class="auth-body">
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div class="row">
                        <!-- Prénom -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <div class="input-group-custom">
                                <i class="fas fa-user"></i>
                                <input type="text" name="prenom" class="form-control" required placeholder="Alice">
                            </div>
                        </div>

                        <!-- Nom -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <div class="input-group-custom">
                                <i class="fas fa-signature"></i>
                                <input type="text" name="nom" class="form-control" required placeholder="Duval">
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Adresse Email</label>
                        <div class="input-group-custom">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-control" required placeholder="alice.duval@etudiant.fr">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" class="form-control" required minlength="8" placeholder="••••••••">
                        </div>
                        <small class="text-muted d-block mt-1">Au minimum 8 caractères</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <div class="input-group-custom">
                            <i class="fas fa-shield-alt"></i>
                            <input type="password" name="password_confirmation" class="form-control" required minlength="8" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-secondary w-100 btn-auth shadow-lg">
                        Créer le compte <i class="fas fa-chevron-right ms-1"></i>
                    </button>
                </form>

                <hr class="my-4" style="border-color: #e2e8f0;">

                <p class="text-center text-muted mb-0 small">
                    Déjà un compte ? <a href="{{ route('login') }}" class="fw-bold text-secondary">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection