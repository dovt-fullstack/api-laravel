<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'image',
        'options',
        'choose',
        'answer',
        'point',
    ];

    protected $casts = [
        'options' => 'json',
        'choose' => 'json',
    ];
   public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
