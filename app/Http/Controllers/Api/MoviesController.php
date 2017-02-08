<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Transformers\MovieTransformer;
use App\Movie;
use App\CastMember;

class MoviesController extends Controller {

    /**
     * Returns a response object for a single movie entity
     */
    protected function movieResult(Movie $movie) {
        $fractal = new Manager;
        $result = new Item($movie, new MovieTransformer);

        $fractal->parseIncludes('cast');

        return response($fractal->createData($result)->toJson(), 200)
                  ->header('Content-Type', 'application/json');
    }

    /**
     * GET: movies
     */
    public function list(Request $request) {
        $movies = Movie::with('cast.actor')->get();

        if (($genreId = $request->input('genre'))) {
            $movies = $movies->where('genre_id', $genreId);
        }

        $fractal = new Manager;
        $result = new Collection($movies, new MovieTransformer);

        $fractal->parseIncludes('cast');

        return response($fractal->createData($result)->toJson(), 201)
                  ->header('Content-Type', 'application/json');
    }

    /**
     * GET: movies/:id
     */
    public function read(Request $request, $id) {
        if (!($movie = Movie::find($id))) {
            return response(json_encode(['ok' => false]), 404)
                ->header('Content-Type', 'application/json');
        }

        return $this->movieResult($movie);
    }

    /**
     * POST: movies
     */
    public function create(Request $request) {
        $this->validate($request, [
            'name'          => 'required|unique:movies|max:255',
            'birth_date'    => 'required|date',
            'bio'           => 'filled'
        ]);

        $movie = Movie::create([
            'name'          => $request->input('name'),
            'birth_date'    => $request->input('birth_date'),
            'bio'           => $request->input('bio')
        ]);

        return $this->movieResult($movie);
    }

    /**
     * PUT: movies/:id
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'name'          => 'filled|unique:movies|max:255',
            'birth_date'    => 'filled|date',
            'bio'           => 'filled'
        ]);

        $movie = Movie::find($id);
        
        $movie->fill([
            'name'          => $request->input('name'),
            'birth_date'    => $request->input('birth_date'),
            'bio'           => $request->input('bio')
        ]);

        $movie->save();

        return $this->movieResult($movie);
    }

    /**
     * Links an actor to a movie or updates their role
     */
    public function castAdd(Request $request, $id) {
        $this->validate($request, [
            'cast'              => 'required|array',
            'cast.*.actor_id'   => 'required',
            'cast.*.role'       => 'required'
        ]);

        $movie = Movie::find($id);

        collect($request->input('cast'))
            ->each(function($_member) use($movie) {
                CastMember
                    ::where('movie_id', $movie->id)
                    ->where('actor_id', $_member['actor_id'])
                    ->delete();

                CastMember::create([
                    'actor_id'  => $_member['actor_id'],
                    'movie_id'  => $movie->id,
                    'role'      => $_member['role']
                ]);
            });
    }

    public function castRemove(Request $request, $id, $actorId) {
        CastMember
            ::where('movie_id', $id)
            ->where('actor_id', $actorId)
            ->delete();

        return response(json_encode(['ok' => true]), 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Upload and link an image to the movie.
     *
     * Images are stored with the public storage engine.
     */
    public function thumbnail(Request $request, $id) {
        $this->validate($request, [
            'thumbnail' => 'required|image'
        ]);

        $movie = Movie::find($id);

        if (!$movie) {
            return response('Movie Not Found', 404);
        }

        $filename = sprintf('movie_thumb_%d.%s', $movie->id, $request->thumbnail->extension());

        $path = $request->thumbnail->storeAs('public', $filename);

        $movie->thumbnail_uri = asset('storage/' . $filename);

        $movie->save();

        return response($movie->thumbnail_uri, 201)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * DELETE: movies/:id
     */
    public function remove(Request $request, $id) {
        $movie = Movie::find($id);

        if (!$movie) {
            return response(json_encode(['ok' => false]), 404)
                  ->header('Content-Type', 'application/json');
        }

        $movie->delete();

        return response(json_encode(['ok' => true]), 200)
                  ->header('Content-Type', 'application/json');
    }

}