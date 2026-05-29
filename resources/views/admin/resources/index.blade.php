@extends('layouts.admin')

@section('title', 'Gestion des Ressources')

@section('content')
<div class="container-fluid p-0">
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-book-medical me-2 text-primary"></i>Ressources Psychologiques</h5>
            <a href="{{ route('admin.resources.create') }}" class="btn btn-primary rounded-pill">
                <i class="fas fa-plus me-1"></i>Ajouter une ressource
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 border-0">Titre</th>
                            <th class="border-0">Description</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Lien / URL</th>
                            <th class="border-0">Date d'ajout</th>
                            <th class="border-0 text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($resources->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Aucune ressource enregistrée. Ajoutez-en une pour les étudiants.</p>
                                </td>
                            </tr>
                        @else
                            @foreach($resources as $resource)
                                <tr>
                                    <td class="px-3 fw-bold text-dark">{{ $resource->titre }}</td>
                                    <td style="max-width: 250px;" class="text-truncate" title="{{ $resource->description }}">
                                        {{ $resource->description ?? '-' }}
                                    </td>
                                    <td>
                                        @if($resource->type === 'article')
                                            <span class="badge bg-primary"><i class="fas fa-file-alt me-1"></i>Article</span>
                                        @elseif($resource->type === 'video')
                                            <span class="badge bg-success"><i class="fas fa-video me-1"></i>Vidéo</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-phone-alt me-1"></i>Contact d'urgence</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($resource->url)
                                            <a href="{{ $resource->url }}" target="_blank" class="text-decoration-none">
                                                <i class="fas fa-external-link-alt me-1"></i>Visiter le lien
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $resource->created_at->format('d/m/Y') }}</td>
                                    <td class="text-end px-3">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.resources.edit', $resource->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>
                                            <form action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette ressource ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
