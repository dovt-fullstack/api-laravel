<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['name', 'topic_id','question_id'];

    // public function topic()
    // {
    //     return $this->belongsTo(TopicPost::class);
    // }

    public function questions()
    {
        return $this->hasMany(Question::class);
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
