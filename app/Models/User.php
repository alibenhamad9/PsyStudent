<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'points',
        'niveau',
        'streak_count',
        'last_activity_date',
        'is_suspended',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_activity_date' => 'date',
    ];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function moods()
    {
        return $this->hasMany(Mood::class);
    }

    public function breathingSessions()
    {
        return $this->hasMany(BreathingSession::class);
    }

    public function badges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Ajoute des points d'XP et gère le passage de niveau.
     */
    public function addPoints(int $xp)
    {
        $this->points += $xp;
        
        // Calcul du seuil de passage au niveau suivant
        // Par exemple : Niveau 1 -> 100 XP, Niveau 2 -> 200 XP, etc.
        $xpPourNiveauSuivant = $this->niveau * 100;
        
        while ($this->points >= $xpPourNiveauSuivant) {
            $this->points -= $xpPourNiveauSuivant;
            $this->niveau += 1;
            $xpPourNiveauSuivant = $this->niveau * 100;
            
            // Attribuer un badge de niveau si nécessaire
            if ($this->niveau === 5) {
                $this->awardBadge('level_5', 'Adepte du Bien-être', 'Atteindre le niveau 5 sur la plateforme.', 'fa-star text-warning');
            } elseif ($this->niveau === 10) {
                $this->awardBadge('level_10', 'Sage Zen', 'Atteindre le niveau 10 sur la plateforme.', 'fa-yin-yang text-purple');
            }
        }
        
        $this->save();
        $this->updateStreak();
    }

    /**
     * Met à jour le streak de jours consécutifs.
     */
    public function updateStreak()
    {
        $aujourdhui = now()->toDateString();
        $hier = now()->subDay()->toDateString();
        
        if (is_null($this->last_activity_date)) {
            $this->streak_count = 1;
        } else {
            $derniereActivite = $this->last_activity_date->toDateString();
            
            if ($derniereActivite === $hier) {
                // Activité consécutive
                $this->streak_count += 1;
                
                // Badges de streak
                if ($this->streak_count === 3) {
                    $this->awardBadge('streak_3', 'Habitude Saine', 'Enregistrer son activité 3 jours consécutifs.', 'fa-fire text-orange');
                } elseif ($this->streak_count === 7) {
                    $this->awardBadge('streak_7', 'Mental d\'Acier', 'Enregistrer son activité 7 jours consécutifs.', 'fa-fire-alt text-danger');
                }
            } elseif ($derniereActivite !== $aujourdhui) {
                // Break dans la série
                $this->streak_count = 1;
            }
        }
        
        $this->last_activity_date = $aujourdhui;
        $this->save();
    }

    /**
     * Attribue un badge à l'utilisateur s'il ne l'a pas déjà.
     */
    public function awardBadge(string $type, string $nom, string $description, string $icone)
    {
        if (!$this->badges()->where('badge_type', $type)->exists()) {
            return $this->badges()->create([
                'badge_type' => $type,
                'nom' => $nom,
                'description' => $description,
                'icone' => $icone,
            ]);
        }
        return null;
    }
}