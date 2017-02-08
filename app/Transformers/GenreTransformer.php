<?php 

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Genre;

class GenreTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'movies'
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
}