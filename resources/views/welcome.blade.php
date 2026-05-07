@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="text-center mb-5">
            <i class="fas fa-heart" style="font-size: 60px; color: #E74C3C;"></i>
            <h1 class="mt-4" style="color: var(--primary);">Bienvenue sur PsyStudent</h1>
            <p class="lead text-muted">Une plateforme innovante pour votre bien-être psychologique</p>
        </div>

        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-brain" style="font-size: 40px; color: var(--primary);"></i>
                        <h5 class="card-title mt-3">Quiz d'Évaluation</h5>
                        <p class="card-text">Évaluez votre bien-être psychologique avec notre quiz scientifique</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-robot" style="font-size: 40px; color: var(--secondary);"></i>
                        <h5 class="card-title mt-3">Chatbot IA</h5>
                        <p class="card-text">Discutez avec notre assistant IA pour des conseils personnalisés</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            @auth
                <a href="/dashboard" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-right"></i> Aller au Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-user-plus"></i> S'inscrire
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection