<?php

namespace App\Observers;

use App\Events\GameDestroyed;
use App\Models\Game;

class GameObserver
{
    /**
     * @param  \App\Models\Game  $game
     * @return void
     */
    public function deleted(Game $game): void
    {
        broadcast(new GameDestroyed($game->id));
    }
}
