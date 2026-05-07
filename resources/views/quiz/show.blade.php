@extends('layouts.app')

@section('title', 'Quiz')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <h2 style="color: var(--primary);">{{ $quiz->titre }}</h2>
        <p class="text-muted">{{ $quiz->description }}</p>

        <form method="POST" action="{{ route('quiz.submit', $quiz->id) }}" id="quizForm">
            @csrf

            @foreach ($quiz->questions as $question)
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title">
                            <span class="badge bg-primary">Question {{ $question->question_number }}/{{ count($quiz->questions) }}</span>
                        </h6>
                        <p class="lead mt-3">{{ $question->question_text }}</p>

                        @if ($question->type === 'choice')
                            <div class="mt-3">
                                @foreach ($question->options as $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="question_{{ $question->id }}"
                                               value="{{ $option->id }}"
                                               id="option_{{ $option->id }}" required>
                                        <label class="form-check-label" for="option_{{ $option->id }}">
                                            {{ $option->option_text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-3">
                                <input type="range" class="form-range"
                                       name="question_{{ $question->id }}"
                                       min="1" max="5" value="3" id="scale_{{ $question->id }}">
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.9rem;">
                                    <span>Pas du tout</span>
                                    <span id="scale_value_{{ $question->id }}">3</span>
                                    <span>Beaucoup</span>
                                </div>
                            </div>
                            <script>
                                document.getElementById('scale_{{ $question->id }}').addEventListener('input', function() {
                                    document.getElementById('scale_value_{{ $question->id }}').textContent = this.value;
                                });
                            </script>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="/dashboard" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check"></i> Soumettre le Quiz
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection