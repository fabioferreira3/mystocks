<?php

namespace Domain\Stock\Projectors;

use Domain\User\Events\UserCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector {

    public function onUserCreated(UserCreated $event)
    {
        $user = $event->user;

        /* Assign Guest role */
        /* Send email confirmation*/

    }

}
