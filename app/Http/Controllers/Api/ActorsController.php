<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Transformers\ActorTransformer;
use App\Transformers\MovieTransformer;
use App\Actor;

class ActorsController extends Controller {

    /**
     * Returns a response object for a single actor entity
     */
    protected function actorResult(Actor $actor) {
        $fractal = new Manager;
        $result = new Item($actor, new ActorTransformer);

        $fractal->parseIncludes('roles.movie');

        return response($fractal->createData($result)->toJson(), 200)
                  ->header('Content-Type', 'application/json');
    }

    /**
     * GET: actors
     */
    public function list(Request $request) {
        $actors = Actor::all();

        $fractal = new Manager;
        $result = new Collection($actors, new ActorTransformer);

        return response($fractal->createData($result)->toJson(), 201)
                  ->header('Content-Type', 'application/json');
    }

    /**
     * GET: actors/:id
     */
    public function read(Request $request, $id) {
        if (!($actor = Actor::find($id))) {
            return response(json_encode(['ok' => false]), 404)
                ->header('Content-Type', 'application/json');
        }

        return $this->actorResult($actor);
    }

    /**
     * POST: actors
     */
    public function create(Request $request) {
        $this->validate($request, [
            'name'          => 'required|unique:actors|max:255',
            'birth_date'    => 'required|date',
            'bio'           => 'filled'
        ]);

        $actor = Actor::create([
            'name'          => $request->input('name'),
            'birth_date'    => $request->input('birth_date'),
            'bio'           => $request->input('bio')
        ]);

        return $this->actorResult($actor);
    }

    /**
     * PUT: actors/:id
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'name'          => 'filled|unique:actors|max:255',
            'birth_date'    => 'filled|date',
            'bio'           => 'filled'
        ]);

        $actor = Actor::find($id);
        
        $actor->fill([
            'name'          => $request->input('name'),
            'birth_date'    => $request->input('birth_date'),
            'bio'           => $request->input('bio')
        ]);

        $actor->save();

        return $this->actorResult($actor);
    }
    
    public function thumbnail(Request $request, $id) {
        $this->validate($request, [
            'thumbnail' => 'required|image'
        ]);

        $actor = Actor::find($id);

        if (!$actor) {
            return response('Actor Not Found', 404);
        }

        $filename = sprintf('actor_thumb_%d.%s', $actor->id, $request->thumbnail->extension());

        $path = $request->thumbnail->storeAs('public', $filename);

        $actor->thumbnail_uri = asset('storage/' . $filename);

        $actor->save();

        return response($actor->thumbnail_uri, 201)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * DELETE: actors/:id
     */
    public function remove(Request $request, $id) {
        $actor = Actor::find($id);

        if (!$actor) {
            return response(json_encode(['ok' => false]), 404)
                  ->header('Content-Type', 'application/json');
        }

        $actor->delete();

        return response(json_encode(['ok' => true]), 200)
                  ->header('Content-Type', 'application/json');
    }

}