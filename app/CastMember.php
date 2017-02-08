<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CastMember extends Model
{
    
    protected $fillable = [
        'role', 
        'actor_id',
        'movie_id'
    ];

    public function actor() {
        return $this->belongsTo('App\Actor');
    }

    public function movie() {
        return $this->belongsTo('App\Movie');
    }
    
}
