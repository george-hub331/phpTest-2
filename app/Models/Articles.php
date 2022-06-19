<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'body',
        'likes',
        'likesIp',
        'views',
        'viewsIp',
        'tags',
        'comments'
    ];

    protected $table = 'articles';

}
