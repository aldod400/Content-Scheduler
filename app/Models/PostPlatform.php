<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostPlatform extends Model
{
    protected $fillable = [
        'post_id',
        'platform_id',
    ];
}
