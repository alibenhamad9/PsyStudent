<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    protected $fillable = ['nom', 'description', 'personnalite'];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}