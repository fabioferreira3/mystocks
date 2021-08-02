<?php

namespace Domain\StoredEvent\Observers;

use Illuminate\Support\Facades\Auth;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class StoredEventObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  EloquentStoredEvent $storedEvent
     * @return void
     */
    public function creating(EloquentStoredEvent $storedEvent)
    {
        $storedEvent->user_id = Auth::id();
    }


}
