<?php

namespace App\Console\Commands;

use App\Jobs\RemoveExpiredGameJob;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveExpiredGames extends Command
{
    /**
     * @var string
     */
    protected $signature = 'remove-games:expired';

    /**
     * @var string
     */
    protected $description = 'Removing expired games';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nowDate = Carbon::now()->toDateTimeString();

        Game::whereDate('expired_at', '<=', $nowDate)->eachById(function (Game $game) {
            RemoveExpiredGameJob::dispatch($game);
        });

        return Command::SUCCESS;
    }
}
