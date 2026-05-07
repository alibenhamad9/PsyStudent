<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function show($id)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($id);
        return view('quiz.show', compact('quiz'));
    }

    public function submit($id, Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        $quiz = Quiz::with('questions.options')->findOrFail($id);

        $scoreTotal = 0;
        $scoreMax = 0;
        $answers = [];

        foreach ($quiz->questions as $question) {
            $scoreMax += $question->points;

            $inputKey = 'question_' . $question->id;
            $userAnswer = $request->input($inputKey);

            if (!$userAnswer) {
                continue;
            }

            if ($question->type === 'choice') {
                $option = $question->options->where('id', $userAnswer)->first();

                if ($option && $option->is_correct) {
                    $scoreTotal += $question->points;
                }

                $answers[$question->id] = [
                    'selected' => $userAnswer,
                    'option_value' => $option->option_value ?? null,
                    'is_correct' => $option->is_correct ?? false
                ];
            }

            if ($question->type === 'scale') {
                $value = (int) $userAnswer;

                $scoreTotal += $value;

                $answers[$question->id] = [
                    'selected_value' => $value,
                    'points' => $value
                ];
            }
        }

        $pourcentage = $scoreMax > 0 ? ($scoreTotal / $scoreMax) * 100 : 0;

        if ($pourcentage >= 70) {
            $statut = 'stable';
        } elseif ($pourcentage >= 40) {
            $statut = 'attention';
        } else {
            $statut = 'critique';
        }

        $evaluation = Evaluation::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score_total' => $scoreTotal,
            'score_max' => $scoreMax,
            'pourcentage' => round($pourcentage, 2),
            'statut' => $statut,
            'details' => json_encode($answers),
            'temps_ecoule' => $request->input('temps', 0),
            'completed_at' => now()
        ]);

        return redirect()->route('results', $evaluation->id);
    }

    public function results($id)
    {
        $evaluation = Evaluation::with(['user', 'quiz.chatbot'])->findOrFail($id);

        if ($evaluation->user_id !== Auth::id()) {
            abort(403);
        }

        return view('quiz.results', compact('evaluation'));
    }
}