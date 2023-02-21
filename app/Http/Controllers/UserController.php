<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Game\GameRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
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
     * @param \App\Http\Requests\User\IndexRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $users = $this->userRepository->loadAll(
            $request->getLimit(),
            $request->getOffset(),
            $request->getFilters(),
            $request->getSort(),
        );

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'user' => $this->userRepository->loadUserById($user->id)
        ]);
    }

    /**
     * @param \App\Http\Requests\User\StoreRequest $request
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
        $this->gameRepository->createNew($newUser->id);

        return response()->json([
            'user' => $this->userRepository->loadUserById($newUser->id)
        ], Response::HTTP_CREATED);
    }

    /**
     * @param \App\Http\Requests\User\UpdateRequest $request
     * @param \App\Models\User                      $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $attributes = [
            'name' => $request->getName(),
            'phone' => $request->getPhone(),
        ];

        $updatedUser = $this->userRepository->update($user, $attributes);

        return response()->json([
            'user' => $this->userRepository->loadUserById($updatedUser->id)
        ]);
    }
}
