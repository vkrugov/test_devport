<?php

namespace App\Http\Controllers\Game;

use App\Events\GameDestroyed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\StoreRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\User;
use App\Repositories\Game\GameRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    /**
     * @var \App\Repositories\Game\GameRepositoryInterface
     */
    private GameRepositoryInterface $gameRepository;

    /**
     * @param \App\Repositories\Game\GameRepositoryInterface $gameRepository
     */
    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param \App\Models\Game $game
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Game $game): JsonResponse
    {
        $game->load('user');

        return response()->json([
            'game' => new GameResource($game),
        ]);
    }

    /**
     * @param \App\Models\Game $game
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Game $game): JsonResponse
    {
        broadcast(new GameDestroyed($game->id));
        $game->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * @param \App\Http\Requests\Game\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $userId = $request->getUserId();
        $user = User::findOrFail($userId);

        $newGame = $this->gameRepository->createNew($user->id);

        return response()->json([
            'game' => new GameResource($newGame),
        ], Response::HTTP_CREATED);
    }
}
