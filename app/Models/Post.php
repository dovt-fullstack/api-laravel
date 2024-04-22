<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = ['name', 'topic_id'];

    // Mối quan hệ: Một bài đăng có nhiều câu hỏi
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'posts', 'post_id', 'question_id');
    }
}
