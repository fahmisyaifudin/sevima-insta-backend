<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $table = 'post_comments';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User');
    }
    
}