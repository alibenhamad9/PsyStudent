@extends('layouts.admin')

@section('title', 'Gestion des Étudiants')

@section('content')
<div class="container-fluid p-0">
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
            <h5 class="card-title fw-bold mb-3 mb-sm-0 text-dark"><i class="fas fa-users me-2 text-primary"></i>Gestion des Utilisateurs</h5>
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par nom, prénom ou email..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 border-0">Nom complet</th>
                            <th class="border-0">Email</th>
                            <th class="border-0">Rôle</th>
                            <th class="border-0 text-center">Niveau / Points XP</th>
                            <th class="border-0 text-center">Streak d'activité</th>
                            <th class="border-0">Statut</th>
                            <th class="border-0 text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Aucun utilisateur trouvé.</p>
                                </td>
                            </tr>
                        @else
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-3">
                                        <div class="fw-bold text-dark">{{ $user->prenom }} {{ $user->nom }}</div>
                                        <small class="text-muted">Inscrit le : {{ $user->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-danger">Administrateur</span>
                                        @else
                                            <span class="badge bg-secondary">Étudiant</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->role !== 'admin')
                                            <span class="fw-bold text-primary">Niv. {{ $user->niveau }}</span>
                                            <div class="text-muted" style="font-size: 0.8rem;">{{ $user->points }} XP</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->role !== 'admin')
                                            @if($user->streak_count > 0)
                                                <span class="badge bg-orange text-white" style="background-color: #f97316;">
                                                    <i class="fas fa-fire me-1"></i>{{ $user->streak_count }} jours
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_suspended)
                                            <span class="badge bg-danger-subtle text-danger"><i class="fas fa-ban me-1"></i>Suspendu</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success"><i class="fas fa-check-circle me-1"></i>Actif</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-3">
                                        @if($user->id !== Auth::id())
                                            <div class="d-inline-flex gap-2">
                                                <!-- Suspend / Reactivate Form -->
                                                <form action="{{ route('admin.users.toggle-suspension', $user->id) }}" method="POST">
                                                    @csrf
                                                    @if($user->is_suspended)
                                                        <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                                            <i class="fas fa-check me-1"></i>Réactiver
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                                            <i class="fas fa-ban me-1"></i>Suspendre
                                                        </button>
                                                    @endif
                                                </form>

                                                <!-- Delete Form -->
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ? Toutes ses données associées seront supprimées.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                        <i class="fas fa-trash me-1"></i>Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted small">Vous-même</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
