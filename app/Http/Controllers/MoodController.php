<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoodController extends Controller
{
    /**
     * Enregistre ou met à jour l'humeur de l'utilisateur pour aujourd'hui.
     */
    public function store(Request $request)
    {
        $request->validate([
            'humeur' => 'required|integer|between:1,4',
            'note' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $aujourdhui = now()->toDateString();

        // Enregistrer ou mettre à jour l'humeur du jour
        $mood = Mood::updateOrCreate(
            [
                'user_id' => $user->id,
                'date_suivi' => $aujourdhui
            ],
            [
                'humeur' => $request->humeur,
                'note' => $request->note,
            ]
        );

        // Attribuer XP (+15 XP pour le mood du jour)
        $user->addPoints(15);

        // Badge du premier suivi d'humeur
        $user->awardBadge(
            'first_mood', 
            'Premier Pas', 
            'Enregistrer son humeur pour la première fois !', 
            'fa-smile text-info'
        );

        return response()->json([
            'success' => true,
            'message' => 'Humeur enregistrée avec succès ! (+15 XP)',
            'streak' => $user->streak_count,
            'niveau' => $user->niveau,
            'points' => $user->points
        ]);
    }

    /**
     * Récupère les données d'humeur pour le graphique hebdomadaire.
     */
    public function getWeeklyTrend()
    {
        $user = Auth::user();
        
        // Prendre les 7 derniers jours d'activité
        $moods = Mood::where('user_id', $user->id)
            ->where('date_suivi', '>=', now()->subDays(6)->toDateString())
            ->orderBy('date_suivi', 'asc')
            ->get();

        // Formater les données pour Chart.js
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = ucfirst($date->locale('fr')->translatedFormat('D d M')); // Forcer locale fr et première lettre en majuscule
            
            $moodOfTheDay = $moods->firstWhere('date_suivi', $date->toDateString());
            $data[] = $moodOfTheDay ? $moodOfTheDay->humeur : null; // null si pas de donnée
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
