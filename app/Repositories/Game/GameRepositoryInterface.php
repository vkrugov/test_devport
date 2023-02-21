<?php

namespace App\Repositories\Game;

use App\Models\Game;

interface GameRepositoryInterface
{
    /**
     * @param int $userId
     *
     * @return \App\Models\Game
     */
    public function createNew(int $userId): Game;
}
