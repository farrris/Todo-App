<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\ResponseService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function __construct(
        protected UserService $userService,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     description="Метод для авторизации пользователя",
     *
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *              @OA\Property(property="email",type="string",example="example@gmail.com"),
     *              @OA\Property(property="password",type="string",example="123456789"),
     *           ),
     *       ),
     *     ),
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="user",
     *                     ref="#/components/schemas/User"
     *                 ),
     *              )
     *          )
     *      )
     * )
     * 
     * @param LoginRequest
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $credentials = ["email" => $validated["email"], "password" => $validated["password"]];

        $token = Auth::attempt($credentials);
        if (!$token) {
            return ResponseService::unauthorized();
        } else {
            return ResponseService::success([
                "user" => Auth::user(),
                "authorization" => [
                    "token" => $token,
                    "type" => "bearer"
                ],
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="register",
     *     tags={"Auth"},
     *     description="Метод для регистрации пользователя",
     *
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               @OA\Property(property="name", type="string", example="Andrey"),
     *               @OA\Property(property="email",type="string", example="example@gmail.com"),
     *               @OA\Property(property="password",type="string", example="123456789")
     *           ),
     *       ),
     *     ),
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="user",
     *                     ref="#/components/schemas/User"
     *                 ),
     *              )
     *          )
     *      )
     * )
     * 
     * @param RegisterRequest
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        $token = Auth::login($user);

        return ResponseService::сreated(
            [
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer'
                ]
            ],
        );
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     operationId="logout",
     *     tags={"Auth"},
     *     description="Метод для выхода из системы",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Unauthorized",
     *      )
     * )
     * 
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        return ResponseService::success(
            [],
            'Successfully logged out'
        );
    }

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     operationId="refresh",
     *     tags={"Auth"},
     *     description="Метод для обновления jwt токенов пользователя",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="user",
     *                     ref="#/components/schemas/User"
     *                 ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="Unauthorized",
     *      )
     * )
     * 
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return ResponseService::success(
            [
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ],
        );
    }

     /**
     * @OA\Get(
     *     path="/api/protected",
     *     operationId="protected",
     *     tags={"Auth"},
     *     description="Метод для получения информации о текущем авторизованном пользователе/проверки авторизации",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(
     *                     property="user",
     *                     ref="#/components/schemas/User"
     *                 ),
     *              )
     *          )
     *      ),
     *      
     * )
     * 
     * @return JsonResponse
     */
    public function protected(): JsonResponse
    {
        return ResponseService::success(
            [
                'user' => Auth::user()
            ]
        );
    }
}