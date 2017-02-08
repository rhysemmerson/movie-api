<?php 

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Actor;
use App\CastMember;

class ActorTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'roles'
    ];

    public function transform(Actor $actor) {
        return [
            'id'            => $actor->id,
            'name'          => $actor->name,
            'birth_date'    => $actor->birth_date,
            'bio'           => $actor->bio
        ];
    }

    public function includeRoles(Actor $actor) {
        return $this->collection($actor->roles, function(CastMember $member) {
            return [
                'role' => $member->role,
                'movie' => $member->movie->name,
                'movie_id' => $member->movie_id
            ];
        });
    }

}