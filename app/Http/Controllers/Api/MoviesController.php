<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use App\Transformers\MovieTransformer;

class MoviesController extends Controller {

    public function moviesList() {
        
        $fractal = new Manager();

        $movies = \App\Movie::all();

        $result = new Collection($movies, new MovieTransformer);

        echo $fractal->createData($result)->toJson();

    }

    public function moviesRead($id) {

    }

}