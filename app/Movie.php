<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    
    protected $fillable = [
        'title', 
        'description',
        'rating',
        'thumbnail_uri'
    ];

    public function genre() {
        return $this->belongsTo('App\Genre');
    }

    public function cast() {
        return $this->hasMany('App\CastMember');
    }

}
