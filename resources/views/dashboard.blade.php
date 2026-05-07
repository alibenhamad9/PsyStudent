@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="mb-4" style="color: var(--primary);">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</h1>

<p class="text-muted">Bienvenue, <strong>{{ Auth::user()->nom_complet }}</strong>!</p>

<!-- Dernière évaluation -->
@if ($lastEvaluation)
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-chart-bar"></i> Dernière Évaluation</h5>
                    <p class="card-text"><strong>Quiz:</strong> {{ $lastEvaluation->quiz->titre }}</p>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 style="color: var(--primary);">{{ number_format($lastEvaluation->pourcentage, 1) }}%</h2>
                            <span class="badge 
                                @if ($lastEvaluation->statut === 'stable') bg-success
                                @elseif ($lastEvaluation->statut === 'attention') bg-warning text-dark
                                @else bg-danger
                                @endif">
                                {{ ucfirst($lastEvaluation->statut) }}
                            </span>
                        </div>
                        <a href="{{ route('results', $lastEvaluation->id) }}" class="btn btn-primary">
                            Voir les résultats <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Quizzes disponibles -->
<div class="row">
    <div class="col-lg-8">
        <h5 class="mb-3" style="color: var(--primary);"><i class="fas fa-list-check"></i> Quizzes Disponibles</h5>

        @foreach ($quizzes as $quiz)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">{{ $quiz->titre }}</h5>
                            <p class="card-text text-muted">{{ $quiz->description }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $quiz->temps_estimé }} minutes |
                                <i class="fas fa-robot"></i> {{ $quiz->chatbot->nom }}
                            </small>
                        </div>
                        <a href="{{ route('quiz.show', $quiz->id) }}" class="btn btn-primary">
                            Commencer <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="col-lg-4">
        <h5 class="mb-3" style="color: var(--secondary);"><i class="fas fa-robot"></i> Chatbot</h5>
        <div class="card">
            <div class="card-body" id="chatbox" style="height: 400px; overflow-y: auto; background: #f8f9fa;">
                <div class="text-center text-muted">
                    <i class="fas fa-comment-dots" style="font-size: 40px;"></i>
                    <p class="mt-2">Dites bonjour au ChatBot!</p>
                </div>
                <div id="messages"></div>
            </div>
            <div class="card-footer">
                <form id="chatForm" onsubmit="sendMessage(event)">
                    <div class="input-group">
                        <input type="text" class="form-control" id="messageInput" placeholder="Votre message..." required>
                        <button class="btn btn-secondary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Historique évaluations -->
@if ($evaluations->count() > 0)
    <div class="row mt-5">
        <div class="col-lg-8">
            <h5 class="mb-3" style="color: var(--primary);"><i class="fas fa-history"></i> Historique des Évaluations</h5>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Quiz</th>
                        <th>Score</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluations as $eval)
                        <tr>
                            <td><strong>{{ $eval->quiz->titre }}</strong></td>
                            <td>{{ number_format($eval->pourcentage, 1) }}%</td>
                            <td>
                                <span class="badge 
                                    @if ($eval->statut === 'stable') bg-success
                                    @elseif ($eval->statut === 'attention') bg-warning text-dark
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst($eval->statut) }}
                                </span>
                            </td>
                            <td><small>{{ $eval->completed_at->format('d/m/Y H:i') }}</small></td>
                            <td>
                                <a href="{{ route('results', $eval->id) }}" class="btn btn-sm btn-outline-primary">
                                    Détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

@section('extra-js')
<script>
function sendMessage(event) {
    event.preventDefault();
    const message = document.getElementById('messageInput').value;

    // Afficher le message de l'utilisateur
    const messagesDiv = document.getElementById('messages');
    messagesDiv.innerHTML += `
        <div class="mb-2">
            <div class="text-end">
                <span class="badge bg-primary">${message}</span>
            </div>
        </div>
    `;

    // Envoyer au serveur
    fetch('{{ route("chatbot.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        messagesDiv.innerHTML += `
            <div class="mb-2">
                <span class="badge bg-secondary">${data.message}</span>
            </div>
        `;
        document.getElementById('messageInput').value = '';
        document.getElementById('chatbox').scrollTop = document.getElementById('chatbox').scrollHeight;
    });
}
</script>
@endsection