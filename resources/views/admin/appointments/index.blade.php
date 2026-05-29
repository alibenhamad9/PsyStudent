@extends('layouts.admin')

@section('title', 'Gestion des Rendez-vous')

@section('content')
<div class="container-fluid p-0">
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-calendar-alt me-2 text-primary"></i>Demandes de Rendez-vous</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 border-0">Étudiant</th>
                            <th class="border-0">Motif de la demande</th>
                            <th class="border-0">Date de demande</th>
                            <th class="border-0">Psychologue assigné</th>
                            <th class="border-0">Date planifiée</th>
                            <th class="border-0">Statut</th>
                            <th class="border-0 text-end px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($appointments->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 text-secondary"></i>
                                    <p class="mb-0">Aucune demande de rendez-vous pour le moment.</p>
                                </td>
                            </tr>
                        @else
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td class="px-3 fw-bold text-dark">
                                        {{ $appointment->user->prenom }} {{ $appointment->user->nom }}
                                        <div class="fw-normal text-muted small">{{ $appointment->user->email }}</div>
                                    </td>
                                    <td style="max-width: 250px;" class="text-truncate" title="{{ $appointment->motif }}">
                                        {{ $appointment->motif }}
                                    </td>
                                    <td>{{ $appointment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($appointment->psychologist)
                                            <span class="fw-semibold text-primary"><i class="fas fa-user-md me-1"></i>{{ $appointment->psychologist }}</span>
                                        @else
                                            <span class="text-muted italic">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appointment->status === 'accepted')
                                            <span class="badge bg-primary-subtle text-primary">
                                                <i class="fas fa-clock me-1"></i>{{ $appointment->date_time->format('d/m/Y H:i') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appointment->status === 'pending')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>En attente</span>
                                        @elseif($appointment->status === 'accepted')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Accepté & Planifié</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Refusé</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-3">
                                        @if($appointment->status === 'pending')
                                            <div class="d-inline-flex gap-2">
                                                <!-- Planifier Modal Button -->
                                                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#planModal-{{ $appointment->id }}">
                                                    <i class="fas fa-calendar-check me-1"></i>Planifier
                                                </button>

                                                <!-- Reject Form -->
                                                <form action="{{ route('admin.appointments.reject', $appointment->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment refuser ce rendez-vous ?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                        <i class="fas fa-times me-1"></i>Refuser
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Modal to Plan / Accept Appointment -->
                                            <div class="modal fade" id="planModal-{{ $appointment->id }}" tabindex="-1" aria-labelledby="planModalLabel-{{ $appointment->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold" id="planModalLabel-{{ $appointment->id }}">Planifier le Rendez-vous</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.appointments.accept', $appointment->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body text-start">
                                                                <div class="mb-3">
                                                                    <p class="text-muted mb-2"><strong>Étudiant :</strong> {{ $appointment->user->prenom }} {{ $appointment->user->nom }}</p>
                                                                    <p class="text-muted mb-3"><strong>Motif :</strong> "{{ $appointment->motif }}"</p>
                                                                </div>
                                                                
                                                                <div class="mb-3">
                                                                    <label for="date_time-{{ $appointment->id }}" class="form-label fw-semibold">Date et heure du rendez-vous</label>
                                                                    <input type="datetime-local" class="form-control" id="date_time-{{ $appointment->id }}" name="date_time" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="psychologist-{{ $appointment->id }}" class="form-label fw-semibold">Nom du Psychologue</label>
                                                                    <input type="text" class="form-control" id="psychologist-{{ $appointment->id }}" name="psychologist" placeholder="Dr. Dupont, Mme. Robert..." required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-primary">Valider & Planifier</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">Aucune action requise</span>
                                        @endif
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
