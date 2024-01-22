<?php

namespace Tests\Feature;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $userNick;
    private User $userIvan;

    private Task $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userNick = User::create(["name" => "Nick", "email" => "nickols@mail.ru", "password" => "987654321"]);
        $this->userIvan = User::create(["name" => "Ivan", "email" => "ivanivanov@gmail.com", "password" => "123456789"]);

        $this->task = Task::create(["title" => "test", "description" => "blablabla", "status" => TaskStatusEnum::Todo->value, "user_id" => $this->userNick->id]);

        Auth::login($this->userNick);
    }

    public function test_my_tasks(): void
    {   
        $response = $this->get("api/tasks");

        $response->assertStatus(200);
    }

    public function test_detail_task(): void
    {
        $response = $this->get("api/tasks/" . $this->task->id);

        $response->assertStatus(200);
    }

    public function test_create_task(): void
    {
        $data = [
            "title" => "test task 2",
            "description" => "blumblumblum"
        ];

        $response = $this->post("api/tasks", $data);

        $response->assertStatus(201);
    }

    public function test_update_task(): void
    {
        $data = [
            "status" => TaskStatusEnum::InProgress->value
        ];

        $response = $this->put("api/tasks/" . $this->task->id, $data);

        $response->assertStatus(200);
    }

    public function test_remove_task(): void
    {
        $response = $this->delete("api/tasks/" . $this->task->id);

        $response->assertStatus(200);
    }

    public function test_task_protected_from_external_interference()
    {
        Auth::login($this->userIvan);
        $data = [
            "status" => TaskStatusEnum::Cancelled->value
        ];

        $response = $this->put("api/tasks/" . $this->task->id, $data);

        $response->assertStatus(403);
    }
}
