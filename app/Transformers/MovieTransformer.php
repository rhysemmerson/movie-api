<?php 

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Movie;
use App\CastMember;

class MovieTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'cast'
    ];

    public function transform(Movie $movie) {
        return [
            'id'            => (int) $movie->id,
            'name'          => $movie->name,
            'description'   => $movie->description,
            'rating'        => (float) $movie->rating,
            'genre'         => $movie->genre->name,
            'genre_id'      => (int) $movie->genre_id,
            'thumbnail_uri' => $movie->thumbnail_uri
        ];
    }
    
    public function includeCast(Movie $movie) {
        return $this->collection($movie->cast, function(CastMember $member) {
            return [
                'role' => $member->role,
                'actor' => $member->actor->name,
                'actor_id' => $member->actor_id
            ];
        });
    }

}