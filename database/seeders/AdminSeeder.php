<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On vérifie si l'admin existe déjà pour éviter les doublons
        if (!User::where('email', 'admin@platform.local')->exists()) {
            User::create([
                'nom' => 'System',
                'prenom' => 'Admin',
                'email' => 'admin@platform.local',
                'password' => Hash::make('Admin12345'),
                'role' => 'admin',
                'points' => 0,
                'niveau' => 1,
                'streak_count' => 0,
            ]);
        }
    }
}
