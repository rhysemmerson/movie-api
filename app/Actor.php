<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    
    public $fillable = [
        'name', 
        'bio',
        'birth_date',
        'age',
        'thumbnail_uri'
    ];

    public function movies() {
        return $this->belongsToMany('App\Movie')->withPivot('role');
    }
}
