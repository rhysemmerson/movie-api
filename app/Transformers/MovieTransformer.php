<?php 

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use \App\Movie;

class MovieTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'cast'
    ];

    public function transform(Movie $movie) {
        return [
            'name' => $movie->name,
            'description' => $movie->description,
            'rating' => (float) $movie->rating
        ];
    }
    
    public function includeCast(Movie $movie) {
        return $this->collection($movie->cast, new CastTransformer);
    }

}