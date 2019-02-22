<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'preview', 'slug'];
    protected $casts = [
        'user_id' => 'int'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
