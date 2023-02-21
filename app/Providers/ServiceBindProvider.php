<?php

namespace App\Providers;

use App\Repositories\Game\GameRepository;
use App\Repositories\Game\GameRepositoryInterface;
use App\Repositories\History\HistoryRepository;
use App\Repositories\History\HistoryRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Win\WinService;
use App\Services\Win\WinServiceInterface;
use Illuminate\Support\ServiceProvider;

class ServiceBindProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(GameRepositoryInterface::class, GameRepository::class);
        $this->app->bind(HistoryRepositoryInterface::class, HistoryRepository::class);
        $this->app->bind(WinServiceInterface::class, WinService::class);
    }
}
