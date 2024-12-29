<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Http\Requests\LoginRequest;
use App\Modules\User\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseTrait;

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userService->createUser(userData: $request->validated());
        $user->access_token = $user->createToken('PersonalAccessToken')->plainTextToken;

        return self::successResponse(__('application.must_verify'), UserResource::make($user));
    }

    public function login(LoginRequest $request)
    {
        $user = User::getUser($request)->first();
        $validationResult = $this->userService->validateUser($user, $request->password);

        if ($validationResult instanceof JsonResponse)
            return $validationResult;

        $user->access_token = $user->createToken('PersonalAccessToken')->plainTextToken;

        return self::successResponse('login successfully', UserResource::make($user));
    }

    public function userProfile(): JsonResponse
    {
        return self::successResponse(responseData: UserResource::make(auth('api')->user()));
    }


    public function logout(Request $request)
    {
        auth('api')->user()->currentAccessToken()->delete();
        //auth('api')->user()->fcmTokens()->whereFcmToken($request->fcm_token)->delete();

        return self::successResponse(__('application.log_out'));
    }

}
