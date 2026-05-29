@extends('layouts.app')

@section('title', 'Exercice de Respiration')

@section('extra-css')
<style>
    .breathing-body {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%) !important;
        color: #f1f5f9;
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        padding: 40px;
        position: relative;
        overflow: hidden;
    }

    .breathing-card {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        z-index: 10;
    }

    .breathing-circle-container {
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 40px 0;
        position: relative;
    }

    .breathing-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: radial-gradient(circle, var(--secondary) 0%, #0d9488 100%);
        box-shadow: 0 0 40px rgba(0, 168, 150, 0.6);
        transition: all 4s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Classes d'animation gérées par JS pour un timing exact */
    .breathing-circle.inhale {
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, #38bdf8 0%, var(--primary) 100%);
        box-shadow: 0 0 60px rgba(56, 189, 248, 0.8);
        transition: all 4s ease-in-out;
    }

    .breathing-circle.hold {
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, #818cf8 0%, #4f46e5 100%);
        box-shadow: 0 0 70px rgba(129, 140, 248, 0.9);
        transition: all 4s ease-in-out;
        animation: pulse 2s infinite ease-in-out;
    }

    .breathing-circle.exhale {
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, var(--secondary) 0%, #0d9488 100%);
        box-shadow: 0 0 40px rgba(0, 168, 150, 0.6);
        transition: all 6s ease-in-out;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .breathing-text {
        font-size: 1.8rem;
        font-weight: 600;
        margin-top: 15px;
        min-height: 40px;
        color: #38bdf8;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .stat-badge {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 10px 20px;
        margin: 5px;
        display: inline-block;
    }

    .stat-badge i {
        color: var(--secondary);
    }

    .btn-control {
        border-radius: 50px;
        padding: 12px 35px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
    }

    .btn-start {
        background: linear-gradient(135deg, var(--secondary) 0%, #0d9488 100%);
        border: none;
        color: white;
    }

    .btn-start:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 168, 150, 0.4);
    }

    .btn-stop {
        background: rgba(239, 68, 68, 0.2);
        border: 2px solid #ef4444;
        color: #fca5a5;
    }

    .btn-stop:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    .progress-bar-container {
        width: 100%;
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        margin-top: 25px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        width: 0%;
        background: var(--secondary);
        transition: width 0.1s linear;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour au Dashboard
    </a>
    <h2 class="mb-0 text-dark"><i class="fas fa-wind"></i> Respiration Zen</h2>
</div>

<div class="breathing-body">
    <div class="breathing-card">
        <h3>Pause Respiration Guidée</h3>
        <p class="text-muted">Prenez un moment pour relâcher les tensions. Suivez le rythme du cercle.</p>

        <!-- Stats -->
        <div class="mb-3">
            <div class="stat-badge">
                <i class="fas fa-history"></i> Sessions : <strong id="total-sessions-badge">{{ $totalSessions }}</strong>
            </div>
            <div class="stat-badge">
                <i class="fas fa-circle-notch"></i> Cycles totaux : <strong id="total-cycles-badge">{{ $totalCycles }}</strong>
            </div>
        </div>

        <!-- Cercle respirant -->
        <div class="breathing-circle-container">
            <div class="breathing-circle" id="breath-circle"></div>
        </div>

        <div class="breathing-text" id="breath-text">Prêt à commencer ?</div>
        
        <div class="mt-3 text-muted" id="cycle-counter-text" style="display: none;">
            Cycle en cours : <span id="current-cycle">0</span> | Temps écoulé : <span id="time-elapsed">0</span>s
        </div>

        <div class="progress-bar-container" id="progress-container" style="display: none;">
            <div class="progress-bar-fill" id="progress-fill"></div>
        </div>

        <!-- Contrôles -->
        <div class="mt-4" id="controls-section">
            <button class="btn btn-control btn-start me-3" id="start-btn" onclick="startExercise()">
                <i class="fas fa-play"></i> Commencer
            </button>
            <button class="btn btn-control btn-stop" id="stop-btn" onclick="stopExercise()" style="display: none;">
                <i class="fas fa-stop"></i> Terminer & Enregistrer
            </button>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    let isRunning = false;
    let timerInterval = null;
    let animationTimeout = null;
    let startTime = null;
    let elapsedSeconds = 0;
    let completedCycles = 0;
    let progressInterval = null;

    const circle = document.getElementById('breath-circle');
    const breathText = document.getElementById('breath-text');
    const startBtn = document.getElementById('start-btn');
    const stopBtn = document.getElementById('stop-btn');
    const cycleCounter = document.getElementById('cycle-counter-text');
    const currentCycleSpan = document.getElementById('current-cycle');
    const timeElapsedSpan = document.getElementById('time-elapsed');
    const progressContainer = document.getElementById('progress-container');
    const progressFill = document.getElementById('progress-fill');

    function startExercise() {
        if (isRunning) return;

        isRunning = true;
        elapsedSeconds = 0;
        completedCycles = 0;
        startTime = Date.now();

        startBtn.style.display = 'none';
        stopBtn.style.display = 'inline-block';
        cycleCounter.style.display = 'block';
        progressContainer.style.display = 'block';

        currentCycleSpan.textContent = completedCycles;
        timeElapsedSpan.textContent = elapsedSeconds;

        // Démarrer le chronomètre
        timerInterval = setInterval(() => {
            elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);
            timeElapsedSpan.textContent = elapsedSeconds;
        }, 1000);

        // Démarrer la boucle de respiration
        runBreathingLoop();
    }

    function runBreathingLoop() {
        if (!isRunning) return;

        // --- PHASE 1 : INSPIRE (4 secondes) ---
        circle.className = 'breathing-circle inhale';
        breathText.textContent = 'Inspirez...';
        breathText.style.color = '#38bdf8';
        startProgressBar(4000);

        animationTimeout = setTimeout(() => {
            if (!isRunning) return;

            // --- PHASE 2 : RETIENS (4 secondes) ---
            circle.className = 'breathing-circle hold';
            breathText.textContent = 'Retenez...';
            breathText.style.color = '#818cf8';
            startProgressBar(4000);

            animationTimeout = setTimeout(() => {
                if (!isRunning) return;

                // --- PHASE 3 : EXPIRE (6 secondes) ---
                circle.className = 'breathing-circle exhale';
                breathText.textContent = 'Expirez...';
                breathText.style.color = '#00A896';
                startProgressBar(6000);

                animationTimeout = setTimeout(() => {
                    if (!isRunning) return;

                    // Un cycle complet est terminé
                    completedCycles++;
                    currentCycleSpan.textContent = completedCycles;

                    // Relancer le cycle suivant
                    runBreathingLoop();
                }, 6000);

            }, 4000);

        }, 4000);
    }

    function startProgressBar(durationMs) {
        clearInterval(progressInterval);
        let start = Date.now();
        
        progressInterval = setInterval(() => {
            let elapsed = Date.now() - start;
            let percent = Math.min((elapsed / durationMs) * 100, 100);
            progressFill.style.width = percent + '%';
            
            if (elapsed >= durationMs) {
                clearInterval(progressInterval);
            }
        }, 50);
    }

    function stopExercise() {
        if (!isRunning) return;

        isRunning = false;
        clearInterval(timerInterval);
        clearInterval(progressInterval);
        clearTimeout(animationTimeout);

        circle.className = 'breathing-circle';
        breathText.textContent = 'Session terminée !';
        breathText.style.color = '#38bdf8';

        startBtn.style.display = 'inline-block';
        stopBtn.style.display = 'none';
        progressContainer.style.display = 'none';

        if (completedCycles > 0) {
            saveSession(elapsedSeconds, completedCycles);
        } else {
            alert("Session trop courte pour être enregistrée. Essayez de faire au moins un cycle complet !");
            cycleCounter.style.display = 'none';
            breathText.textContent = 'Prêt à commencer ?';
        }
    }

    function saveSession(duration, cycles) {
        breathText.textContent = 'Enregistrement de la session...';
        
        fetch('{{ route("breathing.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                duree_secondes: duration,
                cycles_completes: cycles
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour les badges de stats en haut
                document.getElementById('total-sessions-badge').textContent = parseInt(document.getElementById('total-sessions-badge').textContent) + 1;
                document.getElementById('total-cycles-badge').textContent = parseInt(document.getElementById('total-cycles-badge').textContent) + cycles;

                breathText.innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> ${data.message}</span>`;
                
                // Petite alerte temporaire ou notification de gamification
                setTimeout(() => {
                    alert(`Félicitations ! Vous avez gagné 25 XP. Votre niveau actuel : ${data.niveau}`);
                }, 500);
            } else {
                breathText.textContent = 'Erreur lors de l\'enregistrement.';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            breathText.textContent = 'Erreur réseau lors de l\'enregistrement.';
        });
    }
</script>
@endsection
