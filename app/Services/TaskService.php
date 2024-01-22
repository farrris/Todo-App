<?php

namespace App\Services;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function createTask(array $taskData)
    {
        $task = Task::create([
            "title" => $taskData["title"],
            "description" => $taskData["description"],
            "user_id" => Auth::id(),
            "status" => TaskStatusEnum::Todo->value
        ]);

        return $task;
    }

    public function updateTask(Task $task, array $taskData)
    {
        $task->update($taskData);

        return $task;
    }

    public function removeTask(Task $task)
    {
        return $task->delete();
    }
}