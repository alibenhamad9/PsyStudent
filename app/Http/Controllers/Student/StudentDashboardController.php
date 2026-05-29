<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Evaluation;
use App\Models\Mood;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // Si l'utilisateur est suspendu, on le déconnecte avec un message
        if ($user->is_suspended) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été suspendu par l\'administrateur. Veuillez contacter le support.'
            ]);
        }

        // Si c'est l'admin, on le redirige vers le dashboard admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $quizzes = Quiz::with('chatbot')->get();

        $evaluations = Evaluation::with('quiz')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $lastEvaluation = Evaluation::where('user_id', $user->id)
            ->latest()
            ->first();

        // Récupérer l'humeur du jour de l'utilisateur
        $todayMood = Mood::where('user_id', $user->id)
            ->where('date_suivi', now()->toDateString())
            ->first();

        // Récupérer les badges de l'utilisateur
        $badges = $user->badges()->latest()->get();

        // S'assurer que le streak est mis à jour à chaque affichage du dashboard
        if ($user->last_activity_date && $user->last_activity_date->toDateString() === now()->subDay()->toDateString()) {
            $user->updateStreak();
        }

        return view('student.dashboard', [
            'quizzes' => $quizzes,
            'evaluations' => $evaluations,
            'lastEvaluation' => $lastEvaluation,
            'user' => $user,
            'todayMood' => $todayMood,
            'badges' => $badges
        ]);
    }
}
