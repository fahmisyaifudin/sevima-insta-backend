<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = 'post_likes';
    protected $primaryKey = 'id';

    protected $guarded = [];
    
}