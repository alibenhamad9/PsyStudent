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

        $messages = [
            [
                'title' => 'État émotionnel équilibré',
                'text' => 'Votre état général semble stable. Vous démontrez une bonne capacité à gérer votre stress et vos émotions au quotidien.'
            ],
            [
                'title' => 'Maintenir votre bien-être',
                'text' => 'Continuez à préserver vos habitudes positives : sommeil régulier, activité physique, repos mental et échanges sociaux sains.'
            ]
        ];

    } elseif ($status === 'attention') {
        $borderClass = 'border-warning';
        $badgeClass = 'bg-warning text-dark';
        $progressClass = 'bg-warning';
        $alertClass = 'alert-warning';
        $label = 'Attention';

        $messages = [
            [
                'title' => 'Des signes de fatigue mentale détectés',
                'text' => 'Certaines réponses montrent une charge émotionnelle ou un stress qui mérite votre attention. Il est important de ne pas ignorer ces signes.'
            ],
            [
                'title' => 'Prendre soin de votre santé mentale',
                'text' => 'Essayez de ralentir le rythme, de parler avec une personne de confiance et de consulter un professionnel si ces difficultés persistent.'
            ]
        ];

    } else {
        $borderClass = 'border-danger';
        $badgeClass = 'bg-danger text-white';
        $progressClass = 'bg-danger';
        $alertClass = 'alert-danger';
        $label = 'Critique';

        $messages = [
            [
                'title' => 'Votre bien-être mental nécessite une attention immédiate',
                'text' => 'Vos réponses indiquent une souffrance psychologique importante. Vous n’êtes pas seul et il est essentiel de prendre cette situation au sérieux.'
            ],
            [
                'title' => 'Consulter un professionnel peut vous aider',
                'text' => 'Nous vous recommandons fortement de contacter un psychologue, un psychiatre ou un conseiller spécialisé afin d’obtenir un accompagnement adapté et sécurisé.'
            ]
        ];
    }
@endphp

<div class="row">
    <div class="col-lg-10 mx-auto">

        <h2 class="mb-4">Résultats du Quiz</h2>

        <!-- SCORE CARD -->
        <div class="card border-5 {{ $borderClass }}">
            <div class="card-body text-center">

                <h4>{{ $evaluation->quiz->titre ?? 'Quiz' }}</h4>

                <h1 class="display-4 fw-bold">
                    {{ number_format($percent, 1) }}%
                </h1>

                <p class="mb-3">
                    Score :
                    <strong>{{ $evaluation->score_total ?? 0 }}</strong>
                    /
                    <strong>{{ $evaluation->score_max ?? 0 }}</strong>
                </p>

                <span class="badge {{ $badgeClass }} px-4 py-2">
                    {{ $label }}
                </span>

                <div class="progress mt-4" style="height: 28px;">
                    <div
                        class="progress-bar {{ $progressClass }}"
                        data-width="{{ $percent }}"
                    >
                        {{ number_format($percent, 1) }}%
                    </div>
                </div>

            </div>
        </div>

        <!-- INTERPRETATION -->
        <div class="card mt-4">
            <div class="card-body">

                <h4 class="mb-4">Interprétation</h4>

                @foreach ($messages as $msg)
                    <div class="alert {{ $alertClass }} mb-3">
                        <h5 class="fw-bold">
                            {{ $msg['title'] }}
                        </h5>

                        <p class="mb-0">
                            {{ $msg['text'] }}
                        </p>
                    </div>
                @endforeach

            </div>
        </div>

        <!-- CONSEILS -->
        <div class="card mt-4">
            <div class="card-body">

                <h5 class="mb-3">Conseils personnalisés</h5>

                @php
                    $conseils = [];

                    foreach ($details as $d) {
                        if (($d['selected_value'] ?? 3) <= 2) {
                            $conseils[] = 'Essayez d’identifier les sources principales de stress dans votre quotidien.';
                            $conseils[] = 'Accordez-vous des moments de repos et limitez la surcharge mentale.';
                            $conseils[] = 'N’hésitez pas à demander du soutien auprès de proches ou de professionnels.';
                        }
                    }

                    if (empty($conseils)) {
                        $conseils[] = 'Continuez à maintenir un équilibre entre études, repos et vie personnelle.';
                        $conseils[] = 'Gardez des habitudes saines pour préserver votre stabilité émotionnelle.';
                    }

                    $conseils = array_unique($conseils);
                @endphp

                <ul class="list-group">
                    @foreach ($conseils as $c)
                        <li class="list-group-item">
                            {{ $c }}
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>

        <!-- ACTIONS -->
        <div class="row mt-4 mb-5">
            <div class="col-md-6 mb-3 mb-md-0">
                <a href="/dashboard" class="btn btn-primary w-100">
                    Retour au tableau de bord
                </a>
            </div>

            <div class="col-md-6">
                <a href="/dashboard" class="btn btn-secondary w-100">
                    Refaire un quiz
                </a>
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