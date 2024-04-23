<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['name', 'topic_id'];

    public function topic()
    {
        return $this->belongsTo(TopicPost::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
