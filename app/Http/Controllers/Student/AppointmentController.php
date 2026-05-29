<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('student.appointments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'motif' => 'required|string|max:1000',
        ]);

        Appointment::create([
            'user_id' => Auth::id(),
            'date_time' => now(), // Sera défini ou mis à jour par l'admin lorsqu'il accepte/planifie
            'status' => 'pending',
            'motif' => $request->input('motif'),
        ]);

        return redirect()->route('student.appointments.index')->with('success', 'Votre demande de contact a été envoyée avec succès. L\'administrateur va planifier le rendez-vous prochainement.');
    }
}
