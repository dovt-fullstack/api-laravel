<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['name', 'topic_id'];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'post_question', 'post_id', 'question_id');
    }

    public static function getPostWithDetails($id)
    {
        return self::with('topic')->find($id);
    }

    public function topic()
    {
        return $this->belongsTo(TopicPost::class, 'topic_id');
    }

}
