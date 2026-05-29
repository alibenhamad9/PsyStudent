<?php

namespace App\Http\Controllers;

use App\Models\BreathingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BreathingController extends Controller
{
    /**
     * Affiche la page de l'exercice de respiration.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques de respiration
        $totalSessions = BreathingSession::where('user_id', $user->id)->count();
        $totalCycles = BreathingSession::where('user_id', $user->id)->sum('cycles_completes');
        
        return view('breathing', [
            'totalSessions' => $totalSessions,
            'totalCycles' => $totalCycles
        ]);
    }

    /**
     * Enregistre une session de respiration complétée.
     */
    public function store(Request $request)
    {
        $request->validate([
            'duree_secondes' => 'required|integer|min:1',
            'cycles_completes' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // Créer la session
        $session = BreathingSession::create([
            'user_id' => $user->id,
            'duree_secondes' => $request->duree_secondes,
            'cycles_completes' => $request->cycles_completes,
        ]);

        // Attribuer XP (+25 XP pour une session de respiration complétée)
        $user->addPoints(25);

        // Badge de première respiration
        $user->awardBadge(
            'first_breath',
            'Souffle de Vie',
            'Réaliser sa première session de respiration guidée.',
            'fa-wind text-teal'
        );

        // Badge de maître de la respiration (si total sessions >= 5)
        $totalSessions = BreathingSession::where('user_id', $user->id)->count();
        if ($totalSessions >= 5) {
            $user->awardBadge(
                'breath_master',
                'Maître Zen',
                'Compléter plus de 5 sessions d\'exercices de respiration.',
                'fa-compass text-success'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Félicitations pour cette pause bien-être ! (+25 XP)',
            'streak' => $user->streak_count,
            'niveau' => $user->niveau,
            'points' => $user->points
        ]);
    }
}
