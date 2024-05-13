<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryExams extends Model
{
    use HasFactory;
    protected $fillable = ['correct_answer', 'question_check','fail_answer', 'score', 'user_id'];
    protected $casts = [
        'question_check' => 'json',
    ];
}
