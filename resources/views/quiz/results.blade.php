@extends('layouts.app')

@section('title', 'Résultats')

@section('content')
@php
    $details = is_array($evaluation->details)
        ? $evaluation->details
        : json_decode($evaluation->details, true) ?? [];

    $status = $evaluation->statut ?? 'critique';
    $percent = (float) ($evaluation->pourcentage ?? 0);

    if ($status === 'stable') {
        $borderClass = 'border-success';
        $badgeClass = 'bg-success text-white';
        $progressClass = 'bg-success';
        $alertClass = 'alert-success';
        $label = 'Stable';
    } elseif ($status === 'attention') {
        $borderClass = 'border-warning';
        $badgeClass = 'bg-warning text-dark';
        $progressClass = 'bg-warning';
        $alertClass = 'alert-warning';
        $label = 'Attention';
    } else {
        $borderClass = 'border-danger';
        $badgeClass = 'bg-danger text-white';
        $progressClass = 'bg-danger';
        $alertClass = 'alert-danger';
        $label = 'Critique';
    }
@endphp

<div class="row">
    <div class="col-lg-10 mx-auto">

        <h2 class="mb-4">Résultats du Quiz</h2>

        <!-- SCORE CARD -->
        <div class="card border-5 {{ $borderClass }}">
            <div class="card-body">

                <h4>{{ $evaluation->quiz->titre ?? 'Quiz' }}</h4>

                <h1>{{ number_format($percent, 1) }}%</h1>

                <p>
                    Score: {{ $evaluation->score_total ?? 0 }} / {{ $evaluation->score_max ?? 0 }}
                </p>

                <span class="badge {{ $badgeClass }}">
                    {{ $label }}
                </span>

                <div class="progress mt-3">
                    <div class="progress-bar {{ $progressClass }}" data-width="{{ $percent }}">
                        {{ number_format($percent, 1) }}%
                    </div>
                </div>

            </div>
        </div>

        <!-- INTERPRETATION -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="alert {{ $alertClass }}">
                    {{ $label }}
                </div>
            </div>
        </div>

        <!-- CONSEILS -->
        <div class="card mt-4">
            <div class="card-body">

                <h5>Conseils</h5>

                @php
                    $conseils = [];

                    foreach ($details as $d) {
                        if (($d['selected_value'] ?? 3) <= 2) {
                            $conseils[] = 'Amélioration recommandée dans ce domaine.';
                        }
                    }

                    if (empty($conseils)) {
                        $conseils[] = 'État global correct.';
                    }
                @endphp

                <ul>
                    @foreach ($conseils as $c)
                        <li>{{ $c }}</li>
                    @endforeach
                </ul>

            </div>
        </div>

        <!-- ACTIONS -->
        <div class="row mt-4">
            <div class="col-md-6">
                <a href="/dashboard" class="btn btn-primary w-100">Retour</a>
            </div>
            <div class="col-md-6">
                <a href="/dashboard" class="btn btn-secondary w-100">Refaire un quiz</a>
            </div>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.progress-bar').forEach(el => {
        const w = el.getAttribute('data-width') || 0;
        el.style.width = w + '%';
    });
</script>

@endsection