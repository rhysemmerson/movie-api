<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Transformers\GenreTransformer;
use App\Transformers\MovieTransformer;
use App\Genre;

class GenresController extends Controller
{

    protected $validationRules = [
        'name' => 'required|unique:genres|max:255'
    ];

    /**
     * Returns a response object for a single genre entity
     */
    protected function genreResult(Genre $genre) {
        $fractal = new Manager;

        $result = new Item($genre, new GenreTransformer);

        $fractal->parseIncludes('movies');

        return response($fractal->createData($result)->toJson(), 200)
                  ->header('Content-Type', 'application/json');
    }

    /**
     * GET: genres
     */
    public function list(Request $request) {
        $genres = Genres::all();

        $fractal = new Manager;
        $result = new Collection($genres, new GenreTransformer);

        return response($fractal->createData($result)->toJson(), 201)
                  ->header('Content-Type', 'application/json');
    }

    /**
     * GET: genres/:id
     */
    public function read(Request $request, $id) {
        if (!($genre = Genre::find($id))) {
            return response(json_encode(['ok' => false]), 404)
                ->header('Content-Type', 'application/json');
        }
        
        return $this->genreResult($genre);
    }

    /**
     * POST: genres
     */
    public function create(Request $request) {
        $this->validate($request, $this->validationRules);

        $genre = Genre::create([
            'name' => $request->input('name')
        ]);

        return $this->genreResult($genre);
    }

    /**
     * PUT: genres/:id
     */
    public function update(Request $request, $id) {
        $this->validate($request, $this->validationRules);

        $genre = Genre::find($id);
        
        $genre->name = $request->input('name');
        $genre->save();

        return $this->genreResult($genre);
    }

    /**
     * DELETE: genres/:id
     */
    public function remove(Request $request, $id) {
        $genre = Genre::find($id);

        if (!$genre) {
            return response(json_encode(['ok' => false]), 404)
                  ->header('Content-Type', 'application/json');
        }

        $genre->delete();

        return response(json_encode(['ok' => true]), 200)
                  ->header('Content-Type', 'application/json');
    }

}