<?php

namespace App\Http\Controllers\AuthUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthUser\Register\StoreRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\User\UserResource;
use App\Models\Role;
use App\Repositories\Game\GameRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * @var \App\Repositories\User\UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @var \App\Repositories\Game\GameRepositoryInterface
     */
    private GameRepositoryInterface $gameRepository;

    /**
     * @param \App\Repositories\User\UserRepositoryInterface $userRepository
     * @param \App\Repositories\Game\GameRepositoryInterface $gameRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, GameRepositoryInterface $gameRepository)
    {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param \App\Http\Requests\AuthUser\Register\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $attributes = [
            'name' => $request->getName(),
            'phone' => $request->getPhone(),
        ];

        $newUser = $this->userRepository->create($attributes);
        $newUser->setRole(Role::CUSTOMER);
        $newGame = $this->gameRepository->createNew($newUser->id);

        return response()->json([
            'user' => new UserResource($newUser),
            'game' => new GameResource($newGame),
        ], Response::HTTP_CREATED);
    }
}
