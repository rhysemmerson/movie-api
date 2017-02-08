<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    
    protected $fillable = [
        'name', 
        'bio',
        'birth_date',
        'thumbnail_uri'
    ];

    public function roles() {
        return $this->hasMany('App\CastMember');
    }

}
