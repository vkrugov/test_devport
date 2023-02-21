<?php

namespace App\Services\Win;

use App\Models\History;

interface WinServiceInterface
{
    /**
     * @param int $gameId
     *
     * @return \App\Models\History
     */
    public function makeWin(int $gameId): History;
}
