<?php

namespace Domain\User\Events;

use Domain\User\User;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class UserCreated extends ShouldBeStored
{
    /** @var User */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
