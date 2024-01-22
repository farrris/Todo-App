<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskDetailResource;
use App\Http\Resources\TaskListResource;
use App\Models\Task;
use App\Services\ResponseService;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     operationId="tasksIndex",
     *     tags={"Task"},
     *     description="Метод для просмотра списка задач пользователя, авторизованного в системе",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="tasks",
     *                     ref="#/components/schemas/Task"
     *                 ),
     *              )
     *          ),
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = Auth::user()->tasks;

        return ResponseService::success(
            TaskListResource::collection($tasks)
        );
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     operationId="tasksShow",
     *     tags={"Task"},
     *     description="Метод для детального просмотра задачи",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="id задачи",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="task",
     *                     ref="#/components/schemas/Task"
     *                 ),
     *              )
     *          )
     *      ),
     *      @OA\Response(response="404",
     *          description="NOT FOUND"
     *      ),
     *      @OA\Response(response="403",
     *          description="FORBIDDEN"
     *      ),
     * )
     * 
     * @param Task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {   
        $this->authorize("view", $task);

        return ResponseService::success(
            TaskDetailResource::make($task)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/tasks/",
     *     operationId="tasksStore",
     *     tags={"Task"},
     *     description="Метод для создания новой задачи",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              @OA\Property(property="title",type="string",example="test title"),
     *              @OA\Property(property="description",type="string",example="test description"),
     *           ),
     *       ),
     *     ),
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="task",
     *                     ref="#/components/schemas/Task"
     *                 ),
     *              )
     *          )
     *      )
     * )
     * 
     * @param StoreTaskRequest
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());

        return ResponseService::сreated(
            TaskDetailResource::make($task)
        );
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     operationId="tasksUpdate",
     *     tags={"Task"},
     *     description="Метод для обновления задачи",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="id задачи",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              @OA\Property(property="title",type="string",example="test title"),
     *              @OA\Property(property="description",type="string",example="test description"),
     *              @OA\Property(property="status",type="string",example="in progress"),
     *           ),
     *       ),
     *     ),
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="task",
     *                     ref="#/components/schemas/Task"
     *                 ),
     *              )
     *          )
     *      ),
     *      @OA\Response(response="404",
     *          description="NOT FOUND"
     *      ),
     *      @OA\Response(response="403",
     *          description="FORBIDDEN"
     *      ),
     * )
     * 
     * @param Task
     * @param UpdateTaskRequest
     * 
     * @return JsonResponse
     */
    public function update(Task $task, UpdateTaskRequest $request): JsonResponse
    {   
        $this->authorize("update", $task);

        $task = $this->taskService->updateTask($task, $request->validated());

        return ResponseService::success(
            TaskDetailResource::make($task)
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     operationId="tasksDelete",
     *     tags={"Task"},
     *     description="Метод удаляет задачу",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="id задачи",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response="200",
     *          description="OK",
     *     ),
     *     @OA\Response(response="404",
     *          description="NOT FOUND"
     *     ),
     *     @OA\Response(response="403",
     *          description="FORBIDDEN"
     *     ),
     * )
     * 
     * @param Task
     * 
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {   
        $this->authorize("destroy", $task);

        $this->taskService->removeTask($task);

        return ResponseService::success();
    }
}
