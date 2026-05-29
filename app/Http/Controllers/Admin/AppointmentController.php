<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    public function accept(Request $request, $id)
    {
        $request->validate([
            'date_time' => 'required|date|after:now',
            'psychologist' => 'required|string|max:255',
        ]);

        $appointment = Appointment::with('user')->findOrFail($id);
        $appointment->date_time = $request->input('date_time');
        $appointment->psychologist = $request->input('psychologist');
        $appointment->status = 'accepted';
        $appointment->save();

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'accept_appointment',
            'details' => "Rendez-vous accepté pour {$appointment->user->prenom} {$appointment->user->nom} le {$appointment->date_time} avec {$appointment->psychologist}.",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Le rendez-vous a été accepté et planifié avec succès.');
    }

    public function reject($id)
    {
        $appointment = Appointment::with('user')->findOrFail($id);
        $appointment->status = 'rejected';
        $appointment->save();

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'reject_appointment',
            'details' => "Rendez-vous refusé pour {$appointment->user->prenom} {$appointment->user->nom}.",
            'ip_address' => request()->ip()
        ]);

        return redirect()->back()->with('success', 'Le rendez-vous a été refusé.');
    }
}
