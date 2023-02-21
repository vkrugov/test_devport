<?php

namespace App\Repositories\History;

use Illuminate\Pagination\LengthAwarePaginator;

interface HistoryRepositoryInterface
{
    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param array    $filters
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function loadAll(?int $limit, ?int $offset, array $filters = []): LengthAwarePaginator;
}
