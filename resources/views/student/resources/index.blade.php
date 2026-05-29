@extends('layouts.app')

@section('title', 'Ressources Psychologiques')

@section('extra-css')
<style>
    body { background: #f1f5f9 !important; }
    .container-main { background: transparent !important; box-shadow: none !important; padding: 0 !important; margin-top: 20px !important; }

    .page-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);
        position: relative;
        overflow: hidden;
    }

    .page-header::after {
        content: '';
        position: absolute;
        right: -40px;
        bottom: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .resource-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .resource-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }

    .type-badge {
        padding: 5px 14px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .type-article { background: #eff6ff; color: #2563eb; }
    .type-video { background: #fef3c7; color: #b45309; }
    .type-contact { background: #fce7f3; color: #be185d; }
    .type-guide { background: #ecfdf5; color: #065f46; }
    .type-lien { background: #f3e8ff; color: #7c3aed; }
</style>
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i> Retour au Dashboard
    </a>
</div>

<div class="page-header">
    <h2 class="fw-bold mb-1"><i class="fas fa-book-medical me-2"></i>Ressources Psychologiques</h2>
    <p class="mb-0 text-white-50">Articles, guides, vidéos et contacts d'urgence pour prendre soin de votre santé mentale.</p>
</div>

@if($resources->isEmpty())
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-book-open fa-4x text-muted" style="opacity: 0.3;"></i>
        </div>
        <h4 class="fw-bold text-muted">Aucune ressource disponible pour le moment</h4>
        <p class="text-muted">L'administrateur n'a pas encore ajouté de ressources. Revenez bientôt !</p>
    </div>
@else
    <div class="row">
        @foreach($resources as $resource)
            <div class="col-md-6 col-lg-4">
                <div class="resource-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            @php
                                $typeClass = match(strtolower($resource->type ?? '')) {
                                    'article' => 'type-article',
                                    'video', 'vidéo' => 'type-video',
                                    'contact' => 'type-contact',
                                    'guide' => 'type-guide',
                                    default => 'type-lien',
                                };
                                $typeIcon = match(strtolower($resource->type ?? '')) {
                                    'article' => 'fa-newspaper',
                                    'video', 'vidéo' => 'fa-play-circle',
                                    'contact' => 'fa-phone-alt',
                                    'guide' => 'fa-file-alt',
                                    default => 'fa-link',
                                };
                            @endphp
                            <span class="type-badge {{ $typeClass }}">
                                <i class="fas {{ $typeIcon }} me-1"></i> {{ ucfirst($resource->type ?? 'Lien') }}
                            </span>
                            <small class="text-muted">{{ $resource->created_at->diffForHumans() }}</small>
                        </div>

                        <h5 class="fw-bold text-dark mb-2">{{ $resource->titre }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit($resource->description, 120) }}</p>

                        @if($resource->url)
                            <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success btn-sm rounded-pill px-3 w-100">
                                <i class="fas fa-external-link-alt me-1"></i> Consulter la ressource
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
