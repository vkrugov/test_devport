<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\IndexRequest;
use App\Models\Game;
use App\Repositories\History\HistoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class HistoryController extends Controller
{
    /**
     * @var \App\Repositories\History\HistoryRepositoryInterface
     */
    private HistoryRepositoryInterface $historyRepository;

    /**
     * @param \App\Repositories\History\HistoryRepositoryInterface $historyRepository
     */
    public function __construct(HistoryRepositoryInterface $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    /**
     * @param \App\Http\Requests\History\IndexRequest $request
     * @param \App\Models\Game                        $game
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request, Game $game): JsonResponse
    {
        $filters = $request->getFilters();
        $filters['game_id'] = $game->id;

        $histories = $this->historyRepository->loadAll(
            $request->getLimit(),
            $request->getOffset(),
            $filters,
        );

        return response()->json([
            'histories' => $histories
        ]);
    }
}
