@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid p-0">
    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-stat bg-white h-100 p-4 border-0 shadow-sm" style="border-left: 5px solid #6366f1 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.8rem;">Total Étudiants</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ $totalStudents }}</h2>
                    </div>
                    <div class="rounded-circle bg-indigo-subtle p-3" style="background-color: #e0e7ff;">
                        <i class="fas fa-users fa-2x text-indigo" style="color: #6366f1;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat bg-white h-100 p-4 border-0 shadow-sm" style="border-left: 5px solid #10b981 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.8rem;">Conversations IA</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ $totalConversations }}</h2>
                    </div>
                    <div class="rounded-circle bg-emerald-subtle p-3" style="background-color: #d1fae5;">
                        <i class="fas fa-comments fa-2x text-emerald" style="color: #10b981;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat bg-white h-100 p-4 border-0 shadow-sm" style="border-left: 5px solid #f59e0b !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.8rem;">Total Rendez-vous</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ $totalAppointments }}</h2>
                    </div>
                    <div class="rounded-circle bg-amber-subtle p-3" style="background-color: #fef3c7;">
                        <i class="fas fa-calendar-check fa-2x text-amber" style="color: #f59e0b;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes de détresse psychologique -->
    <div class="row g-4 mb-4">
        <!-- Conversations de détresse / Crisis -->
        <div class="col-lg-6">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Alertes IA : Signaux de détresse</h5>
                    <span class="badge bg-danger">{{ $crisisConversations->count() }}</span>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    @if($crisisConversations->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-heart-circle-check fa-3x mb-3 text-success"></i>
                            <p class="mb-0">Aucun signal de détresse grave détecté par l'IA.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($crisisConversations as $convo)
                                <div class="list-group-item p-3 border-0 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0 text-dark">{{ $convo->user->prenom }} {{ $convo->user->nom }}</h6>
                                        <small class="text-muted">{{ $convo->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 text-muted bg-light p-2 rounded" style="font-size: 0.9rem; border-left: 3px solid #ef4444;">
                                        <strong>Étudiant :</strong> "{{ $convo->message_user }}"
                                    </p>
                                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                        <strong>IA :</strong> "{{ Str::limit($convo->message_chatbot, 120) }}"
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cas critiques / Évaluations Critiques -->
        <div class="col-lg-6">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-warning"><i class="fas fa-radiation me-2"></i>Cas Critiques (Évaluations)</h5>
                    <span class="badge bg-warning text-dark">{{ $criticalStudents->count() }}</span>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    @if($criticalStudents->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-smile-beam fa-3x mb-3 text-success"></i>
                            <p class="mb-0">Aucun état critique signalé lors des évaluations.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($criticalStudents as $eval)
                                <div class="list-group-item p-3 border-0 border-bottom d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">{{ $eval->user->prenom }} {{ $eval->user->nom }}</h6>
                                        <small class="text-muted">Évaluation : {{ $eval->quiz->titre }} • Score : {{ $eval->score_total }}/{{ $eval->score_max }} ({{ $eval->pourcentage }}%)</small>
                                    </div>
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Critique</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Connexions et logs -->
    <div class="row g-4">
        <!-- Connexions et activité récente -->
        <div class="col-lg-6">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-user-clock me-2 text-primary"></i>Activité Récente des Étudiants</h5>
                </div>
                <div class="card-body p-0">
                    @if($activeUsers->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <p class="mb-0">Aucune activité récente enregistrée.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 px-3">Étudiant</th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Dernière activité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeUsers as $aUser)
                                        <tr>
                                            <td class="px-3 fw-semibold">{{ $aUser->prenom }} {{ $aUser->nom }}</td>
                                            <td>{{ $aUser->email }}</td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">
                                                    {{ $aUser->last_activity_date ? $aUser->last_activity_date->format('d/m/Y') : 'Non renseigné' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Logs d'actions admin -->
        <div class="col-lg-6">
            <div class="card bg-white border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-clipboard-list me-2 text-primary"></i>Journal des Actions Admin</h5>
                </div>
                <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                    @if($adminLogs->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <p class="mb-0">Aucune action enregistrée pour le moment.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush" style="font-size: 0.9rem;">
                            @foreach($adminLogs as $log)
                                <div class="list-group-item p-3 border-0 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-primary">{{ $log->admin->prenom }} {{ $log->admin->nom }}</strong>
                                        <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="text-dark">{{ $log->details }}</div>
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;"><i class="fas fa-laptop me-1"></i>IP : {{ $log->ip_address }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
