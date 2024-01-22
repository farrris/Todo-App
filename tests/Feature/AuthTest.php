<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $registeredUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registeredUser = User::create(["name" => "Nick", "email" => "nickols@mail.ru", "password" => "987654321"]);

    }
    /**
     * A basic feature test example.
     */
    public function test_register_new_user(): void
    {

        $data = [
            "name" => "Ivan",
            "email" => "ivanivanov@gmail.com",
            "password" => "123456789"
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(201);
    }

    public function test_login_user(): void
    {
        $data = [
            "email" => "nickols@mail.ru",
            "password" => "987654321"
        ];

        $response = $this->post('/api/login', $data);

        $response->assertStatus(200);
    }

    public function test_logout_user(): void
    {
        $token = Auth::login($this->registeredUser);

        $response = $this->post("/api/logout");

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user(): void
    {
        $response = $this->get("/api/protected");

        $response->assertStatus(401);
    }
}
