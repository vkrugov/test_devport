<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Models\Game;
use App\Services\Win\WinServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WinController extends Controller
{
    /**
     * @var \App\Services\Win\WinServiceInterface
     */
    private WinServiceInterface $winService;

    /**
     * @param \App\Services\Win\WinServiceInterface $winService
     */
    public function __construct(WinServiceInterface $winService)
    {
        $this->winService = $winService;
    }

    /**
     * @param \App\Models\Game $game
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Game $game): JsonResponse
    {
        $newHistory = $this->winService->makeWin($game->id);

        return response()->json([
            'win' => new HistoryResource($newHistory),
        ], Response::HTTP_CREATED);
    }
}
