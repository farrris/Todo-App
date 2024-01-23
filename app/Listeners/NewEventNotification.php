<?php

namespace App\Listeners;

use App\Events\NewEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NewEventNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewEvent $event): void
    {
        Log::info("Новое событие в системе: " . $event->eventTodo->title);
    }
}
