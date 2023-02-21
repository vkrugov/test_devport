<?php

namespace App\Repositories\Game;

use App\Models\Game;
use Carbon\Carbon;

class GameRepository implements GameRepositoryInterface
{
    /**
     * @param int $userId
     *
     * @return \App\Models\Game
     */
    public function createNew(int $userId): Game
    {
        $game = new Game();
        $game->user_id = $userId;
        $game->url = $this->makeUrl();
        $game->expired_at = Carbon::now()->addDays(Game::DAYS_TO_EXPIRED);
        $game->save();

        return $game;
    }

    /**
     * @return string
     */
    private function makeUrl(): string
    {
        $newUrl = uniqid();
        $isUrlExists = Game::where('url', $newUrl)->exists();

        return $isUrlExists ? $this->makeUrl() : $newUrl;
    }
}
