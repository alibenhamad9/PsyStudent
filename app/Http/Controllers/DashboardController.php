<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        $quizzes = Quiz::with('chatbot')->get();

        $evaluations = Evaluation::with('quiz')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $lastEvaluation = Evaluation::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('dashboard', [
            'quizzes' => $quizzes,
            'evaluations' => $evaluations,
            'lastEvaluation' => $lastEvaluation,
            'user' => $user
        ]);
    }
}