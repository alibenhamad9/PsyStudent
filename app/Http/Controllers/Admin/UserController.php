<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function toggleSuspension($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte.');
        }

        $user->is_suspended = !$user->is_suspended;
        $user->save();

        // Enregistrer l'action dans le journal
        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => $user->is_suspended ? 'suspend_user' : 'activate_user',
            'details' => "Compte de l'utilisateur {$user->prenom} {$user->nom} ({$user->email}) " . ($user->is_suspended ? 'suspendu' : 'réactivé') . ".",
            'ip_address' => request()->ip()
        ]);

        $statusMessage = $user->is_suspended ? 'Le compte a été suspendu.' : 'Le compte a été réactivé.';
        return redirect()->back()->with('success', $statusMessage);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Enregistrer l'action dans le journal
        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'delete_user',
            'details' => "Utilisateur supprimé : {$user->prenom} {$user->nom} ({$user->email}).",
            'ip_address' => request()->ip()
        ]);

        $user->delete();

        return redirect()->back()->with('success', "L'utilisateur a été supprimé définitivement.");
    }
}
