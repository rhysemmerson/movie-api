<?php 

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Genre;
use App\Movie;
use App\Actor;
use App\CastMember;

class GenreTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'movies', 'actors'
    ];

    public function transform(Genre $genre) {
        return [
            'id' => (int) $genre->id,
            'name' => $genre->name
        ];
    }

    public function includeMovies(Genre $genre) {
        return $this->collection($genre->movies, new MovieTransformer);
    }

    /* include actors in $genre */
    public function includeActors(Genre $genre) {
        $actors = collect($genre->movies)
                    ->flatMap(function(Movie $movie) {
                        return $movie->cast; 
                    })
                    ->map(function(CastMember $member) {
                        return $member->actor;
                    })
                    ->unique('id');
        
        return $this->collection($actors, new ActorTransformer);
    }
}