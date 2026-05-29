<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreathingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'duree_secondes',
        'cycles_completes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
