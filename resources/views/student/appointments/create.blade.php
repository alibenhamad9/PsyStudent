@extends('layouts.app')

@section('title', 'Demander un rendez-vous')

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

    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        background: white;
        padding: 30px;
    }
</style>
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('student.appointments.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i> Retour aux rendez-vous
    </a>
</div>

<div class="page-header">
    <h2 class="fw-bold mb-1"><i class="fas fa-calendar-plus me-2"></i>Demander un rendez-vous</h2>
    <p class="mb-0 text-white-50">Décrivez votre besoin et notre équipe planifiera un rendez-vous avec un psychologue pour vous.</p>
</div>

<div class="form-card">
    <form action="{{ route('student.appointments.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="motif" class="form-label fw-bold text-dark">
                <i class="fas fa-pen me-1 text-primary"></i> Motif de votre demande
            </label>
            <textarea 
                name="motif" 
                id="motif" 
                class="form-control @error('motif') is-invalid @enderror" 
                rows="6" 
                placeholder="Décrivez brièvement pourquoi vous souhaitez consulter un psychologue... (stress, anxiété, difficultés personnelles, etc.)"
                required
            >{{ old('motif') }}</textarea>
            @error('motif')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text text-muted mt-2">
                <i class="fas fa-lock me-1"></i> Votre demande est strictement confidentielle et ne sera lue que par l'administrateur de la plateforme.
            </div>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-paper-plane me-1"></i> Envoyer ma demande
            </button>
            <a href="{{ route('student.appointments.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                Annuler
            </a>
        </div>
    </form>
</div>

<div class="mt-4 p-4 rounded-3" style="background: #eff6ff; border: 1px solid #bfdbfe;">
    <h6 class="fw-bold text-primary mb-2"><i class="fas fa-info-circle me-1"></i> Comment ça fonctionne ?</h6>
    <ol class="text-muted small mb-0">
        <li class="mb-1">Vous remplissez le formulaire avec le motif de votre demande.</li>
        <li class="mb-1">L'administrateur examine votre demande et vous attribue un psychologue.</li>
        <li class="mb-1">Vous recevrez une notification avec la date et l'heure du rendez-vous.</li>
        <li>Consultez la page "Mes Rendez-vous" pour suivre l'état de votre demande.</li>
    </ol>
</div>
@endsection
