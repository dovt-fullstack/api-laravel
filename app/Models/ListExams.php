<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListExams extends Model
{
    protected $fillable = ['correct_answer', 'fail_answer','score','user_id','question_check'];


}
