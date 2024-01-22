<?php

namespace App\Services;

use App\Models\Event;

class EventService
{   
    public function getEvents()
    {
        $events = Event::all();

        return $events;
    }

    public function createEvent(array $eventData): Event
    {
        $event = Event::create([
            "title" => $eventData["title"],
            "description" => $eventData["description"],
            "date" => $eventData["date"]
        ]);

        return $event;
    }

    public function removeEvent(Event $event): bool
    {
        return $event->delete();
    }
}