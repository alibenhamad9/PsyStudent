@extends('layouts.app')

@section('title', 'Mes Rendez-vous')

@section('extra-css')
<style>
    body { background: #f1f5f9 !important; }
    .container-main { background: transparent !important; box-shadow: none !important; padding: 0 !important; margin-top: 20px !important; }
    
    .page-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.15);
    }

    .appointment-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        background: white;
        transition: all 0.3s;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .appointment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-pending { background: #fef3c7; color: #b45309; }
    .status-accepted { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i> Retour au Dashboard
    </a>
    <a href="{{ route('student.appointments.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
        <i class="fas fa-plus me-1"></i> Demander un rendez-vous
    </a>
</div>

<div class="page-header">
    <h2 class="fw-bold mb-1"><i class="fas fa-calendar-alt me-2"></i>Mes Rendez-vous</h2>
    <p class="mb-0 text-white-50">Suivez l'état de vos demandes de rendez-vous avec un psychologue.</p>
</div>

@if($appointments->isEmpty())
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-calendar-plus fa-4x text-muted" style="opacity: 0.3;"></i>
        </div>
        <h4 class="fw-bold text-muted">Aucun rendez-vous pour le moment</h4>
        <p class="text-muted">Vous n'avez pas encore fait de demande. Cliquez sur le bouton ci-dessus pour demander un rendez-vous.</p>
        <a href="{{ route('student.appointments.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">
            <i class="fas fa-plus me-1"></i> Faire ma première demande
        </a>
    </div>
@else
    @foreach($appointments as $appointment)
        <div class="appointment-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-comment-medical me-2 text-primary"></i>Motif de la demande</h6>
                        <p class="text-muted mb-3">{{ $appointment->motif ?? 'Non renseigné' }}</p>
                        
                        <div class="d-flex flex-wrap gap-3 text-muted small">
                            <span><i class="fas fa-clock me-1"></i> Demandé le {{ $appointment->created_at->format('d/m/Y à H:i') }}</span>
                            @if($appointment->status === 'accepted' && $appointment->date_time)
                                <span class="text-success fw-semibold"><i class="fas fa-calendar-check me-1"></i> Planifié le {{ $appointment->date_time->format('d/m/Y à H:i') }}</span>
                            @endif
                            @if($appointment->psychologist)
                                <span class="text-primary fw-semibold"><i class="fas fa-user-md me-1"></i> {{ $appointment->psychologist }}</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($appointment->status === 'pending')
                            <span class="status-badge status-pending"><i class="fas fa-hourglass-half me-1"></i> En attente</span>
                        @elseif($appointment->status === 'accepted')
                            <span class="status-badge status-accepted"><i class="fas fa-check-circle me-1"></i> Accepté</span>
                        @elseif($appointment->status === 'rejected')
                            <span class="status-badge status-rejected"><i class="fas fa-times-circle me-1"></i> Refusé</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
@endsection
