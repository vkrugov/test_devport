<?php

namespace App\Http\Controllers\AuthAdmin;

use App\Events\Login;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Admin\LoginRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    /**
     * @param \App\Http\Requests\Auth\Admin\LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!$token = auth()->attempt($request->only(['email', 'password']))) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['success' => true]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function self(): JsonResponse
    {
        $user = Auth::user()->load(['roles']);

        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    /**
     * @param string $token
     * @param int    $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $user = Auth::user();
        event(new Login($user));

        return response()->json([
            'auth' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ],
            'user' => new UserResource($user->load(['roles'])),
        ], $statusCode);
    }
}
