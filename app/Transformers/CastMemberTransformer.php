<?php 

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CastMemberTransformer extends TransformerAbstrat {

    protected $availableIncludes = [
        'actor'
    ];

    public function transform(App\CastMember $member) {
        return [
            'role' => $member->role
        ];
    }

    public function includeActor(App\CastMember $member) {
        return $this->item($member->actor, new ActorTransformer);
    }
}