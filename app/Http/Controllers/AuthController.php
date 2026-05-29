<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_suspended) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte a été suspendu par l\'administrateur. Veuillez contacter le support.',
                ]);
            }

            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/'
            ],
            'prenom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'points' => 0,
            'niveau' => 1,
            'streak_count' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('student.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}