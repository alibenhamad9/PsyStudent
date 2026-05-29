@extends('layouts.admin')

@section('title', 'Ajouter une Ressource')

@section('content')
<div class="container-fluid p-0">
    <div class="card bg-white border-0 shadow-sm col-lg-8 mx-auto">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-plus-circle me-2 text-primary"></i>Ajouter une nouvelle Ressource</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.resources.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="titre" class="form-label fw-semibold">Titre de la Ressource</label>
                    <input type="text" class="form-control" id="titre" name="titre" placeholder="Entrez le titre..." value="{{ old('titre') }}" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label fw-semibold">Type de ressource</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="" disabled selected>Sélectionnez le type...</option>
                        <option value="article" {{ old('type') === 'article' ? 'selected' : '' }}>Article</option>
                        <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Vidéo</option>
                        <option value="contact" {{ old('type') === 'contact' ? 'selected' : '' }}>Contact d'urgence</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="url" class="form-label fw-semibold">URL / Lien (Optionnel)</label>
                    <input type="url" class="form-control" id="url" name="url" placeholder="https://example.com/..." value="{{ old('url') }}">
                    <div class="form-text">Lien vers l'article externe, la vidéo YouTube ou le site web de contact d'urgence.</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description / Contenu (Optionnel)</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description de la ressource ou numéro/informations si c'est un contact d'urgence...">{{ old('description') }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary rounded-pill px-4">Annuler</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
