<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EventTest extends TestCase
{   
    use RefreshDatabase;

    private User $user;
    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->event = Event::factory()->create();
        
        Auth::login($this->user);
    }

    public function test_get_events(): void
    {
        $response = $this->get("api/events");

        $response->assertStatus(200);
    }

    public function test_register_new_event(): void
    {
        $data = [
            "title" => "test event",
            "description" => "test description",
            "date" => "2024-01-22 17:33:39"
        ];

        $response = $this->post("api/events", $data);

        $response->assertStatus(201);
    }

    public function test_remove_event(): void
    {
        $response = $this->delete("api/events/" . $this->event->id);

        $response->assertStatus(200);
    }
}
