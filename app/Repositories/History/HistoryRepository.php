<?php

namespace App\Repositories\History;

use App\Http\Resources\HistoryResource;
use App\Models\History;
use App\Models\Role;
use Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class HistoryRepository implements HistoryRepositoryInterface
{
    private const DEFAULT_LIMIT = 10;
    private const DEFAULT_OFFSET = 0;

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param array    $filters
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function loadAll(?int $limit, ?int $offset, array $filters = []): LengthAwarePaginator
    {
        $limit = $limit ?? self::DEFAULT_LIMIT;
        $offset = $offset ?? self::DEFAULT_OFFSET;
        $page = ($offset - $limit) / $limit;

        $query = History::query();

        if (array_key_exists('game_id', $filters)) {
            $query->where('game_id', $filters['game_id']);
        }

        $total = $query->count();
        $historyData = $query->limit($limit)
                             ->offset($offset)
                             ->orderBy('created_at', 'desc')
                             ->get();

        return new LengthAwarePaginator(HistoryResource::collection($historyData), $total, $limit, $page);
    }
}
