@extends('layouts.app')

@section('title', 'Bienvenue')

@section('extra-css')
<style>
    /* Design haut de gamme pour la page d'accueil */
    .hero-section {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(0, 168, 150, 0.08) 100%);
        border-radius: 24px;
        padding: 60px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        margin-bottom: 50px;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -100px;
        left: -100px;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
        z-index: 1;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0, 168, 150, 0.05) 0%, transparent 70%);
        z-index: 1;
    }

    .hero-logo {
        width: 80px;
        height: 80px;
        border-radius: 22px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px auto;
        color: white;
        font-size: 2.2rem;
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.25);
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        letter-spacing: -1px;
        color: #1e293b;
        margin-bottom: 15px;
        position: relative;
        z-index: 2;
    }

    .hero-title span {
        background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: #64748b;
        max-width: 650px;
        margin: 0 auto 35px auto;
        line-height: 1.6;
        position: relative;
        z-index: 2;
    }

    /* Piliers du bien-être */
    .pillar-card {
        background: white;
        border: none;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }

    .pillar-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.07);
    }

    .pillar-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 20px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.03);
    }

    .icon-primary {
        background: rgba(59, 130, 246, 0.1);
        color: var(--primary);
    }

    .icon-secondary {
        background: rgba(0, 168, 150, 0.1);
        color: var(--secondary);
    }

    .icon-accent {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .icon-heart {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .pillar-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .pillar-desc {
        font-size: 0.95rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 0;
    }

    /* Citation inspirante */
    .quote-box {
        background: white;
        border-radius: 20px;
        padding: 30px;
        border-left: 5px solid var(--secondary);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        margin-bottom: 50px;
        text-align: left;
        position: relative;
    }

    .quote-box::after {
        content: '"';
        position: absolute;
        right: 30px;
        bottom: -20px;
        font-size: 6rem;
        color: rgba(0, 168, 150, 0.05);
        font-family: Georgia, serif;
        line-height: 1;
    }

    .quote-text {
        font-size: 1.1rem;
        font-style: italic;
        color: #334155;
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .quote-author {
        font-size: 0.85rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* CTAs */
    .cta-btn-group {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }

    .btn-cta {
        padding: 14px 40px;
        font-size: 1.05rem;
        font-weight: 700;
        border-radius: 50px;
        transition: all 0.3s;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        
        <!-- Section Hero -->
        <div class="hero-section">
            <div class="hero-logo">
                <i class="fas fa-heartbeat"></i>
            </div>
            <h1 class="hero-title">Votre Espace de <span>Bien-être</span></h1>
            <p class="hero-subtitle">
                PsyStudent vous accompagne au quotidien dans la gestion de votre stress, de vos émotions et dans le suivi de votre équilibre psychologique. Un espace bienveillant et sécurisé, conçu spécialement pour vous.
            </p>

            <div class="cta-btn-group">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-cta shadow-lg">
                        <i class="fas fa-chart-line me-2"></i> Accéder à mon Espace (Dashboard)
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-cta shadow-lg">
                        <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-cta" style="border-width: 2px;">
                        <i class="fas fa-user-plus me-2"></i> Créer un compte
                    </a>
                @endauth
            </div>
        </div>

        <!-- Section Citation Inspirante -->
        <div class="quote-box">
            <p class="quote-text" id="inspiration-quote">"Le bien-être n'est pas une destination, c'est un voyage quotidien. Prenez le temps de respirer, d'écouter votre cœur et de cultiver votre paix intérieure."</p>
            <span class="quote-author" id="inspiration-author">Conseil PsyStudent du jour</span>
        </div>

        <!-- Section Piliers (Ce que propose la plateforme de manière élégante, sans faire doublon) -->
        <h4 class="fw-bold text-center text-dark mb-4"><i class="fas fa-star text-warning me-2"></i> Ce que PsyStudent fait pour vous</h4>
        <div class="row g-4 mb-5">
            <!-- Suivi d'Humeur -->
            <div class="col-md-6 col-lg-3">
                <div class="pillar-card">
                    <div class="pillar-icon-box icon-primary">
                        <i class="fas fa-smile"></i>
                    </div>
                    <h5 class="pillar-title">Suivi d'Humeur</h5>
                    <p class="pillar-desc">
                        Enregistrez votre météo intérieure chaque jour en un clic pour identifier vos tendances émotionnelles et observer l'évolution de votre bien-être.
                    </p>
                </div>
            </div>

            <!-- Respiration Guidée -->
            <div class="col-md-6 col-lg-3">
                <div class="pillar-card">
                    <div class="pillar-icon-box icon-secondary">
                        <i class="fas fa-wind"></i>
                    </div>
                    <h5 class="pillar-title">Respiration Zen</h5>
                    <p class="pillar-desc">
                        Utilisez notre guide respiratoire interactif (cycle 4-4-6s) pour apaiser instantanément les pics d'anxiété et vous recentrer pendant vos révisions.
                    </p>
                </div>
            </div>

            <!-- Évaluations -->
            <div class="col-md-6 col-lg-3">
                <div class="pillar-card">
                    <div class="pillar-icon-box icon-accent">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h5 class="pillar-title">Évaluations</h5>
                    <p class="pillar-desc">
                        Passez des tests scientifiques validés pour mesurer votre niveau de stress et de fatigue, et recevez des indicateurs fiables sur votre santé mentale.
                    </p>
                </div>
            </div>

            <!-- Gamification & Entraide -->
            <div class="col-md-6 col-lg-3">
                <div class="pillar-card">
                    <div class="pillar-icon-box icon-heart">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h5 class="pillar-title">Progression & Zen</h5>
                    <p class="pillar-desc">
                        Relevez des défis bien-être, gagnez des points d'XP, maintenez votre série d'activité quotidienne et débloquez de magnifiques badges inspirants !
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('extra-js')
<script>
    // Petit widget de citations dynamiques pour apporter de la poésie dès l'entrée !
    const quotes = [
        { text: "Le bien-être n'est pas une destination, c'est un voyage quotidien. Prenez le temps de respirer.", author: "Conseil PsyStudent du jour" },
        { text: "Tu n'as pas besoin de tout contrôler. Parfois, il suffit de respirer, de faire confiance et de lâcher prise.", author: "Pensée positive" },
        { text: "Prendre soin de sa santé mentale n'est pas de l'égoïsme, c'est une nécessité absolue pour avancer sereinement.", author: "Conseil de psychologue" },
        { text: "Les tempêtes ne durent pas éternellement. Derrière chaque nuage se trouve une force tranquille en vous.", author: "Inspiration bien-être" }
    ];

    document.addEventListener('DOMContentLoaded', () => {
        const randomIndex = Math.floor(Math.random() * quotes.length);
        document.getElementById('inspiration-quote').textContent = `"${quotes[randomIndex].text}"`;
        document.getElementById('inspiration-author').textContent = quotes[randomIndex].author;
    });
</script>
@endsection