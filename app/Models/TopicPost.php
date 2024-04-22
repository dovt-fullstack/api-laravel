<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicPost extends Model
{
    protected $table = 'topics';
    protected $fillable = ['name'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
?>
