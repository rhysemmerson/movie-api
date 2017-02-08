<?php 

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\CastMember;

class CastMemberTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'actor',
        'movie'
    ];

    public function transform(CastMember $member) {
        return [
            'role' => $member->role
        ];
    }

    public function includeActor(CastMember $member) {
        return $this->item($member->actor, new ActorTransformer);
    }

    public function includeMovie(CastMember $member) {
        return $this->item($member->movie, new MovieTransformer);
    }
    
}