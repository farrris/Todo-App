<?php

namespace App\Http\Controllers\Api;

use App\Events\NewEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventService;
use App\Services\ResponseService;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{   
    public function __construct(
        protected EventService $eventService
    ) {   
    }

    /**
     * @OA\Get(
     *     path="/api/events",
     *     operationId="eventsIndex",
     *     tags={"Event"},
     *     description="Метод для просмотра всех событий на платформе",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="events",
     *                     ref="#/components/schemas/Event"
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
        $events = $this->eventService->getEvents();

        return ResponseService::success(
            EventResource::collection($events)    
        );
    }

    /**
     * @OA\Post(
     *     path="/api/events/",
     *     operationId="eventsStore",
     *     tags={"Event"},
     *     description="Метод для регистрации нового события",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              @OA\Property(property="title",type="string",example="test title"),
     *              @OA\Property(property="description",type="string",example="test description"),
     *              @OA\Property(property="date",type="datetime",example="2024-01-22 17:33:39"),
     *           ),
     *       ),
     *     ),
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="event",
     *                    ref="#/components/schemas/Event"
     *                 ),
     *              )
     *          )
     *      )
     * )
     * 
     * @param StoreEventRequest
     * @return JsonResponse
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = $this->eventService->createEvent($request->validated());

        NewEvent::dispatch($event);

        return ResponseService::сreated(
            EventResource::make($event)
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     operationId="eventsDelete",
     *     tags={"Event"},
     *     description="Метод удаляет событие",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="id события",
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
     * )
     * 
     * @param Event
     * 
     * @return JsonResponse
     */
    public function destroy(Event $event): JsonResponse
    {
        $this->eventService->removeEvent($event);

        return ResponseService::success();
    }
}
