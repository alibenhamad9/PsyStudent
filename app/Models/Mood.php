<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'humeur',
        'note',
        'date_suivi'
    ];

    protected $casts = [
        'date_suivi' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEmojiAttribute()
    {
        return match ((int)$this->humeur) {
            1 => '😔',
            2 => '😐',
            3 => '🙂',
            4 => '😄',
            default => '😐',
        };
    }

    public function getLabelAttribute()
    {
        return match ((int)$this->humeur) {
            1 => 'Triste / Fatigué',
            2 => 'Neutre / Moyen',
            3 => 'Bien / Content',
            4 => 'Très bien / Épanoui',
            default => 'Moyen',
        };
    }
}
