<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['titre', 'description', 'chatbot_id', 'temps_estimé'];

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
