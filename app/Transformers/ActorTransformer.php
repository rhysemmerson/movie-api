<?php 

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ActorTransformer extends TransformerAbstrat {

    protected $availableIncludes = [
        'roles'
    ];

    public function transform(App\Actor $actor) {
        return [
            'role' => $actor->name
        ];
    }

    public function includeRoles(App\Actor $actor) {
        return $this->item($actor->roles, new CastMemberTransformer);
    }

}