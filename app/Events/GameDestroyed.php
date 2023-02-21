<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameDestroyed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    private int $gameId;

    /**
     * @param int $gameId
     */
    public function __construct(int $gameId)
    {
        $this->gameId = $gameId;
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'success' => true
        ];
    }

    /**
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('game.' . $this->gameId);
    }
}
