<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Consultation;
use App\Models\Appointment;
use App\Models\Evaluation;
use App\Models\Mood;
use App\Models\AdminLog;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistiques globales
        $totalStudents = User::where('role', 'student')->count();
        $totalConversations = Consultation::count();
        $totalAppointments = Appointment::count();
        
        // Activité récente (10 dernières évaluations et humeurs)
        $recentEvaluations = Evaluation::with(['user', 'quiz'])->latest()->take(5)->get();
        $recentMoods = Mood::with('user')->latest()->take(5)->get();
        
        // Alertes psychologiques
        $crisisConversations = Consultation::with('user')
            ->where('type_interaction', 'crisis')
            ->latest()
            ->get();
            
        $criticalStudents = Evaluation::with('user')
            ->where('statut', 'critique')
            ->latest()
            ->get()
            ->unique('user_id');

        // Activité de connexion / Dernière activité des utilisateurs
        $activeUsers = User::where('role', 'student')
            ->whereNotNull('last_activity_date')
            ->orderBy('last_activity_date', 'desc')
            ->take(5)
            ->get();

        // Journal d'actions admin
        $adminLogs = AdminLog::with('admin')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalConversations',
            'totalAppointments',
            'recentEvaluations',
            'recentMoods',
            'crisisConversations',
            'criticalStudents',
            'activeUsers',
            'adminLogs'
        ));
    }
}
