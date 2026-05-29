@extends('layouts.app')

@section('title', 'Dashboard')

@section('extra-css')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Design System moderne et premium */
    body {
        background: #f1f5f9 !important; /* Fond gris clair doux */
    }

    .container-main {
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin-top: 20px !important;
    }

    .dashboard-header {
        background: linear-gradient(135deg, var(--primary) 0%, #1e3a8a 100%);
        color: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::after {
        content: '';
        position: absolute;
        right: -50px;
        bottom: -50px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
    }

    .premium-card {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        background: white !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 25px;
        overflow: hidden;
    }

    .premium-card:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08) !important;
    }

    .card-header-custom {
        padding: 20px 24px;
        background: white;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 700;
        font-size: 1.1rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body-custom {
        padding: 24px;
    }

    /* Mood Tracker */
    .mood-btn {
        font-size: 2.2rem;
        border: 2px solid transparent;
        background: transparent;
        padding: 10px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .mood-btn:hover {
        transform: scale(1.25);
    }

    .mood-btn.selected {
        border-color: var(--secondary);
        background: rgba(0, 168, 150, 0.1);
        transform: scale(1.2);
    }

    /* Gamification Widgets */
    .xp-bar {
        height: 12px;
        background-color: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .xp-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--secondary) 0%, #0d9488 100%);
        border-radius: 10px;
        transition: width 0.5s ease-out;
    }

    .streak-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fde68a;
        color: #b45309;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 700;
        box-shadow: 0 4px 6px rgba(180, 83, 9, 0.05);
    }

    .streak-fire {
        font-size: 1.2rem;
        animation: flame 1.5s infinite alternate;
    }

    @keyframes flame {
        0% { transform: scale(1); filter: drop-shadow(0 0 2px rgba(239, 68, 68, 0.5)); }
        100% { transform: scale(1.15); filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.8)); }
    }

    .badge-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
        transition: all 0.3s;
    }

    .badge-item:hover .badge-icon {
        transform: rotate(15deg) scale(1.1);
        background: #e2e8f0;
    }

    /* SOS Crise */
    .crisis-alert-box {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 2px solid #ef4444;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.1);
    }

    /* Floating Chat */
    #chatbox {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
    }

    .chat-bubble-user {
        background: var(--primary);
        color: white;
        border-radius: 18px 18px 0px 18px;
        padding: 10px 16px;
        display: inline-block;
        max-width: 80%;
    }

    .chat-bubble-bot {
        background: #e2e8f0;
        color: #1e293b;
        border-radius: 18px 18px 18px 0px;
        padding: 10px 16px;
        display: inline-block;
        max-width: 80%;
    }
</style>
@endsection

@section('content')
<!-- Bouton permanent SOS Crise -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted">Tableau de bord étudiant</span>
    <div class="d-flex gap-2">
        <a href="{{ route('student.appointments.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="fas fa-calendar-alt"></i> Mes Rendez-vous
        </a>
        <a href="{{ route('student.resources.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
            <i class="fas fa-book-medical"></i> Ressources
        </a>
        <button class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#sosModal">
            <i class="fas fa-phone-alt"></i> Besoin d'aide immédiate ? (SOS)
        </button>
    </div>
</div>

<!-- Header de bienvenue -->
<div class="dashboard-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold mb-2">Bonjour, {{ Auth::user()->prenom }} ! 👋</h1>
            <p class="mb-0 text-white-50">Prenez soin de votre esprit aujourd'hui. Comment vous sentez-vous ?</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="streak-badge">
                <span class="streak-fire">🔥</span>
                <span>Série de {{ $user->streak_count }} {{ Str::plural('jour', $user->streak_count) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Zone de détection de crise dynamique (cachée par défaut) -->
<div class="crisis-alert-box" id="dynamic-crisis-alert" style="display: none;">
    <div class="d-flex align-items-start gap-3">
        <div class="bg-danger text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
            <i class="fas fa-exclamation-triangle fa-lg"></i>
        </div>
        <div>
            <h4 class="text-danger fw-bold mb-2">Nous sommes là pour vous aider en urgence</h4>
            <p class="text-dark mb-3">Notre chatbot a détecté que vous traversez peut-être une période très difficile ou de crise. Votre sécurité et votre bien-être sont notre priorité absolue. N'hésitez pas à contacter immédiatement ces professionnels bienveillants et gratuits :</p>
            <div class="row g-3">
                <div class="col-md-4 col-sm-6">
                    <div class="card border-0 bg-white shadow-sm p-3 rounded-lg h-100">
                        <strong class="text-danger"><i class="fas fa-phone-alt"></i> 80103666</strong>
                        <small class="text-muted">Association Tunisienne de Prévention du Suicide (ATPS) - 24/7</small>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card border-0 bg-white shadow-sm p-3 rounded-lg h-100">
                        <strong class="text-primary"><i class="fas fa-phone-alt"></i> 71600037</strong>
                        <small class="text-muted">Contact d'urgence en santé mentale (Hopital psychiatrique Razi) - 24/7</small>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card border-0 bg-white shadow-sm p-3 rounded-lg h-100">
                        <strong class="text-dark"><i class="fas fa-ambulance"></i> 190</strong>
                        <small class="text-muted">Urgences médicales (Ambulance) En cas de danger immédiat pour votre vie.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Colonne Principale (Gauche) -->
    <div class="col-lg-8">
        
        <!-- Section Mood Tracker -->
        <div class="premium-card card">
            <div class="card-header-custom">
                <i class="fas fa-smile text-warning"></i> Suivi d'Humeur quotidien
            </div>
            <div class="card-body-custom">
                <div id="mood-tracker-container" class="text-center py-2">
                    @if($todayMood)
                        <div class="alert alert-success border-0 shadow-sm rounded-lg d-inline-block px-4 py-3">
                            <span class="fs-2 mb-2 d-block">{{ $todayMood->emoji }}</span>
                            <h5 class="fw-bold mb-1">Humeur du jour enregistrée !</h5>
                            <p class="text-muted mb-0">Vous vous sentez : <strong>{{ $todayMood->label }}</strong></p>
                            @if($todayMood->note)
                                <small class="text-muted d-block mt-2">"{{ $todayMood->note }}"</small>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mb-3">Enregistrez votre humeur aujourd'hui pour gagner <strong>+15 XP</strong> et maintenir votre série !</p>
                        <div class="d-flex justify-content-center gap-4 mb-3">
                            <button class="mood-btn" onclick="selectMood(1, '😔')" title="Triste/Fatigué">😔</button>
                            <button class="mood-btn" onclick="selectMood(2, '😐')" title="Neutre/Moyen">😐</button>
                            <button class="mood-btn" onclick="selectMood(3, '🙂')" title="Bien/Content">🙂</button>
                            <button class="mood-btn" onclick="selectMood(4, '😄')" title="Très bien/Épanoui">😄</button>
                        </div>
                        <div id="mood-note-area" style="display: none;" class="max-w-md mx-auto">
                            <div class="input-group mb-3">
                                <input type="text" id="mood-note" class="form-control" placeholder="Ajouter une courte note (optionnel)...">
                                <button class="btn btn-secondary px-4" onclick="submitMood()">Valider</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dernière évaluation & Exercice Respiration Raccourci -->
        <div class="row">
            <div class="col-md-6">
                @if ($lastEvaluation)
                    <div class="premium-card card h-100">
                        <div class="card-header-custom">
                            <i class="fas fa-chart-bar text-primary"></i> Dernière Évaluation
                        </div>
                        <div class="card-body-custom d-flex flex-column justify-content-between h-100">
                            <div>
                                <h6 class="fw-bold text-muted mb-1">{{ $lastEvaluation->quiz->titre }}</h6>
                                <div class="d-flex align-items-baseline gap-2 mb-3">
                                    <h2 class="fw-bold text-primary mb-0">{{ number_format($lastEvaluation->pourcentage, 1) }}%</h2>
                                    <span class="badge 
                                        @if ($lastEvaluation->statut === 'stable') bg-success-subtle text-success
                                        @elseif ($lastEvaluation->statut === 'attention') bg-warning-subtle text-warning-emphasis
                                        @else bg-danger-subtle text-danger
                                        @endif rounded-pill px-3 py-1">
                                        {{ ucfirst($lastEvaluation->statut) }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('results', $lastEvaluation->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                                Voir les détails <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="premium-card card h-100">
                        <div class="card-header-custom">
                            <i class="fas fa-chart-bar text-primary"></i> Évaluation
                        </div>
                        <div class="card-body-custom text-center py-4">
                            <p class="text-muted">Vous n'avez pas encore passé d'évaluation de bien-être.</p>
                            <span class="text-muted small">Faites le premier pas ci-dessous !</span>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="col-md-6">
                <div class="premium-card card h-100">
                    <div class="card-header-custom">
                        <i class="fas fa-wind text-teal"></i> Pause Respiration
                    </div>
                    <div class="card-body-custom d-flex flex-column justify-content-between h-100">
                        <div>
                            <p class="text-muted mb-3">Stressé ou fatigué ? Prenez 2 minutes pour respirer au rythme d'un cycle guidé relaxant et gagnez <strong>+25 XP</strong>.</p>
                        </div>
                        <a href="{{ route('breathing.index') }}" class="btn btn-secondary btn-sm w-100 mt-auto">
                            Commencer l'exercice <i class="fas fa-wind ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quizzes disponibles -->
        <h5 class="fw-bold mb-3 text-dark mt-4"><i class="fas fa-tasks text-primary me-2"></i> Quizzes disponibles</h5>
        @foreach ($quizzes as $quiz)
            <div class="premium-card card mb-3">
                <div class="card-body-custom">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-slate-800">{{ $quiz->titre }}</h5>
                            <p class="text-muted small mb-2">{{ $quiz->description }}</p>
                            <div class="d-flex gap-3 text-muted small">
                                <span><i class="fas fa-clock"></i> {{ $quiz->temps_estimé }} mins</span>
                                <span><i class="fas fa-robot"></i> {{ $quiz->chatbot->nom }}</span>
                            </div>
                        </div>
                        <a href="{{ route('quiz.show', $quiz->id) }}" class="btn btn-primary px-4 py-2 rounded-pill font-semibold">
                            Répondre <i class="fas fa-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Graphique des tendances hebdomadaires d'humeur -->
        <div class="premium-card card mt-4">
            <div class="card-header-custom">
                <i class="fas fa-chart-line text-success"></i> Courbe d'Humeur Hebdomadaire
            </div>
            <div class="card-body-custom">
                <canvas id="weeklyTrendChart" style="max-height: 250px; width: 100%;"></canvas>
            </div>
        </div>

    </div>

    <!-- Colonne Latérale (Droite) -->
    <div class="col-lg-4">
        
        <!-- Section Gamification / Niveau -->
        <div class="premium-card card">
            <div class="card-header-custom">
                <i class="fas fa-trophy text-warning"></i> Niveau & Progression
            </div>
            <div class="card-body-custom text-center">
                <div class="position-relative d-inline-block mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 70px; height: 70px; font-size: 1.8rem; font-weight: 800;">
                        {{ $user->niveau }}
                    </div>
                </div>
                <h5 class="fw-bold mb-1">Niveau {{ $user->niveau }}</h5>
                <p class="text-muted small mb-3">Progression vers le niveau {{ $user->niveau + 1 }}</p>
                
                <div class="xp-bar mb-2">
                    <div class="xp-bar-fill" id="xp-bar-fill" style="width: {{ ($user->points / ($user->niveau * 100)) * 100 }}%"></div>
                </div>
                <div class="d-flex justify-content-between text-muted small mb-4">
                    <span>XP: <strong id="current-xp-text">{{ $user->points }}</strong></span>
                    <span>Seuil: <strong>{{ $user->niveau * 100 }} XP</strong></span>
                </div>

                <!-- Badges de l'utilisateur -->
                <h6 class="fw-bold text-start text-dark mb-3"><i class="fas fa-medal me-1"></i> Vos Badges ({{ $badges->count() }})</h6>
                @if($badges->count() > 0)
                    <div class="d-flex flex-wrap gap-3 justify-content-start">
                        @foreach($badges as $badge)
                            <div class="badge-item text-center" style="width: calc(25% - 10px);" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $badge->nom }} : {{ $badge->description }}">
                                <div class="badge-icon mx-auto mb-1">
                                    <i class="fas {{ $badge->icone }}"></i>
                                </div>
                                <small class="d-block text-truncate small font-medium">{{ $badge->nom }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small text-start my-2">Vous n'avez pas encore débloqué de badges. Enregistrez votre humeur ou faites des exercices pour les débloquer !</p>
                @endif
            </div>
        </div>

        <!-- Section Chatbot -->
        <div class="premium-card card">
            <div class="card-header-custom">
                <i class="fas fa-robot text-teal"></i> Chatbot d'Écoute IA
            </div>
            <div class="card-body-custom d-flex flex-column" style="padding: 15px;">
                <div id="chatbox" style="height: 320px; overflow-y: auto; background: #f8fafc; border-radius: 12px; margin-bottom: 15px; border: 1px solid #f1f5f9;">
                    <div class="text-center text-muted py-4" id="chat-placeholder">
                        <i class="fas fa-comment-dots fa-2x mb-2 text-teal-300"></i>
                        <p class="small mb-0">Je suis votre assistant d'écoute bienveillant. Parlez-moi librement de vos émotions.</p>
                    </div>
                    <div id="messages" class="d-flex flex-column gap-2"></div>
                </div>
                
                <form id="chatForm" onsubmit="sendMessage(event)">
                    <div class="input-group">
                        <input type="text" class="form-control rounded-pill-start" id="messageInput" placeholder="Votre message..." required style="border-radius: 20px 0 0 20px; padding-left: 18px;">
                        <button class="btn btn-secondary px-3" type="submit" style="border-radius: 0 20px 20px 0;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Modal SOS Crise permanent -->
<div class="modal fade" id="sosModal" tabindex="-1" aria-labelledby="sosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-danger text-white border-0 py-3" style="border-radius: 16px 16px 0 0;">
                <h5 class="modal-title fw-bold" id="sosModalLabel"><i class="fas fa-heartbeat"></i> Numéros d'Urgence et d'Écoute</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted">Si vous êtes en détresse, que vous ressentez de l'angoisse extrême ou que vous avez des pensées suicidaires, ne restez pas seul. Des personnes formées et bienveillantes sont à votre écoute gratuitement 24h/24 :</p>
                <div class="d-flex flex-column gap-3 mt-3">
                    <div class="p-3 bg-light rounded-lg border-start border-danger border-4">
                        <h6 class="fw-bold text-danger mb-1"><i class="fas fa-phone-alt"></i> 80103666 - Association Tunisienne de Prévention du Suicide (ATPS)</h6>
                        <span class="text-muted small">Disponible 24/7.</span>
                    </div>
                    <div class="p-3 bg-light rounded-lg border-start border-primary border-4">
                        <h6 class="fw-bold text-primary mb-1"><i class="fas fa-phone-alt"></i> 71600037 - Contact d'urgence en santé mentale (Hopital psychiatrique Razi)</h6>
                        <span class="text-muted small">Disponible 24/7.</span>
                    </div>
                    <div class="p-3 bg-light rounded-lg border-start border-dark border-4">
                        <h6 class="fw-bold text-dark mb-1"><i class="fas fa-ambulance"></i> 190 - Urgences médicales (Ambulance)</h6>
                        <span class="text-muted small">En cas de danger immédiat pour votre vie.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    // Initialisation des tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Charger le graphique hebdomadaire d'humeur
        loadWeeklyTrendChart();
    });

    let selectedHumeurVal = null;

    function selectMood(valeur, emoji) {
        selectedHumeurVal = valeur;
        
        // Retirer la classe selected de tous les boutons
        document.querySelectorAll('.mood-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
        
        // Ajouter la classe selected au bouton cliqué
        event.target.classList.add('selected');
        
        // Afficher la zone de note
        document.getElementById('mood-note-area').style.display = 'block';
    }

    function submitMood() {
        if (!selectedHumeurVal) return;

        const note = document.getElementById('mood-note').value;

        fetch('{{ route("mood.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                humeur: selectedHumeurVal,
                note: note
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher le message d'enregistrement
                const emojis = {1: '😔', 2: '😐', 3: '🙂', 4: '😄'};
                const labels = {1: 'Triste / Fatigué', 2: 'Neutre / Moyen', 3: 'Bien / Content', 4: 'Très bien / Épanoui'};
                
                document.getElementById('mood-tracker-container').innerHTML = `
                    <div class="alert alert-success border-0 shadow-sm rounded-lg d-inline-block px-4 py-3">
                        <span class="fs-2 mb-2 d-block">${emojis[selectedHumeurVal]}</span>
                        <h5 class="fw-bold mb-1">Humeur du jour enregistrée !</h5>
                        <p class="text-muted mb-0">Vous vous sentez : <strong>${labels[selectedHumeurVal]}</strong></p>
                        ${note ? `<small class="text-muted d-block mt-2">"${note}"</small>` : ''}
                    </div>
                `;

                // Mettre à jour l'XP
                const progressFill = document.getElementById('xp-bar-fill');
                const nextLevelXp = data.niveau * 100;
                progressFill.style.width = ((data.points / nextLevelXp) * 100) + '%';
                document.getElementById('current-xp-text').textContent = data.points;

                // Recharger le graphique
                loadWeeklyTrendChart();

                // Notifier l'XP gagné
                alert(`${data.message}\nVotre niveau actuel : ${data.niveau}`);
                location.reload(); // Pour actualiser l'XP et le streak proprement
            }
        });
    }

    function loadWeeklyTrendChart() {
        fetch('{{ route("mood.trend") }}')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('weeklyTrendChart').getContext('2d');
            
            // Map humeurs vers des labels et émojis sur l'axe Y
            const yLabels = {
                1: '😔 Triste',
                2: '😐 Neutre',
                3: '🙂 Bien',
                4: '😄 Super'
            };

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Votre Humeur',
                        data: data.data,
                        borderColor: '#00A896',
                        backgroundColor: 'rgba(0, 168, 150, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#00A896',
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 1,
                            max: 4,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return yLabels[value] || '';
                                }
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    }

    function sendMessage(event) {
        event.preventDefault();
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        if (!message) return;

        // Cacher le placeholder
        const placeholder = document.getElementById('chat-placeholder');
        if (placeholder) placeholder.style.display = 'none';

        // Afficher le message de l'utilisateur
        const messagesDiv = document.getElementById('messages');
        messagesDiv.innerHTML += `
            <div class="d-flex justify-content-end mb-2">
                <div class="chat-bubble-user shadow-sm">
                    ${escapeHtml(message)}
                </div>
            </div>
        `;

        messageInput.value = '';
        const chatbox = document.getElementById('chatbox');
        chatbox.scrollTop = chatbox.scrollHeight;

        // Envoyer au serveur
        fetch('{{ route("chatbot.message") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Afficher le message du chatbot
            messagesDiv.innerHTML += `
                <div class="d-flex justify-content-start mb-2">
                    <div class="chat-bubble-bot shadow-sm">
                        ${data.message.replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
            chatbox.scrollTop = chatbox.scrollHeight;

            // Déclencher le widget de crise si le serveur l'indique
            if (data.trigger_crisis) {
                document.getElementById('dynamic-crisis-alert').style.display = 'block';
                // Scroller vers le haut pour rendre le bloc d'urgence bien visible
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messagesDiv.innerHTML += `
                <div class="d-flex justify-content-start mb-2">
                    <div class="chat-bubble-bot shadow-sm border border-danger text-danger">
                        Erreur lors de la communication avec l'assistant. Veuillez réessayer.
                    </div>
                </div>
            `;
            chatbox.scrollTop = chatbox.scrollHeight;
        });
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
@endsection
