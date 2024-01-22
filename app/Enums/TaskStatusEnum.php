<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case Todo = "todo";
    case InProgress = "in progress";
    case Completed = "completed";
    case Cancelled = "cancelled";
}