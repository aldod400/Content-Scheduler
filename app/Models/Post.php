<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image_url',
        'status',
        'scheduled_time',
        'user_id',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'post_platforms');
    }
}
