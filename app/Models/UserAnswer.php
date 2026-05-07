<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = ['evaluation_id', 'question_id', 'selected_option_id', 'selected_value', 'is_correct'];
    public function evaluation() { return $this->belongsTo(Evaluation::class); }
    public function question() { return $this->belongsTo(Question::class); }
    public function option() { return $this->belongsTo(QuestionOption::class, 'selected_option_id'); }
}