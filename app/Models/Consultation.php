<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = ['user_id', 'chatbot_id', 'message_user', 'message_chatbot', 'type_interaction'];
    public function user() { return $this->belongsTo(User::class); }
    public function chatbot() { return $this->belongsTo(Chatbot::class); }
}