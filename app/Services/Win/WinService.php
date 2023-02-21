<?php

namespace App\Services\Win;

use App\Models\History;
use App\Services\Win\Exceptions\UnprocessableWinResult;
use App\Services\Win\WinHandlers\LessTreeHundredHandler;
use App\Services\Win\WinHandlers\MoreNineHundredHandler;
use App\Services\Win\WinHandlers\MoreSixHundredHandler;
use App\Services\Win\WinHandlers\MoreTreeHundredHandler;

class WinService implements WinServiceInterface
{
    private const MIN_WIN_RESULT = 0;
    private const MAX_WIN_RESULT = 1000;

    /**
     * @param int $gameId
     *
     * @return \App\Models\History
     * @throws \App\Services\Win\Exceptions\UnprocessableWinResult
     */
    public function makeWin(int $gameId): History
    {
        $winResult = $this->generateWinResult();

        if ($this->isLoseResult($winResult)) {
            return $this->saveWin($gameId, $winResult, 0);
        }

        $winSum = $this->calculateWinSum($winResult);

        return $this->saveWin($gameId, $winResult, $winSum);
    }

    /**
     * @return int
     */
    private function generateWinResult(): int
    {
        return rand(self::MIN_WIN_RESULT, self::MAX_WIN_RESULT);
    }

    /**
     * @param int $winResult
     *
     * @return bool
     */
    private function isLoseResult(int $winResult): bool
    {
        return $winResult % 2 !== 0;
    }

    /**
     * @param int   $gameId
     * @param int   $winResult
     * @param float $winSum
     *
     * @return \App\Models\History
     */
    private function saveWin(int $gameId, int $winResult, float $winSum): History
    {
        $history = new History();
        $history->game_id = $gameId;
        $history->result = $winResult;
        $history->win = $winSum * 100;
        $history->save();

        return $history;
    }

    /**
     * @param int $winResult
     *
     * @return float
     * @throws \App\Services\Win\Exceptions\UnprocessableWinResult
     */
    private function calculateWinSum(int $winResult): float
    {
        $winHandlers = [
            MoreNineHundredHandler::class,
            MoreSixHundredHandler::class,
            MoreTreeHundredHandler::class,
            LessTreeHundredHandler::class,
        ];

        foreach ($winHandlers as $winHandler) {
            /** @var \App\Services\Win\WinHandlers\WinCalculatorInterface $winCalculator */
            $winCalculator = new $winHandler($winResult);

            if ($winCalculator->check()) {
                return $winCalculator->calculate();
            }
        }

        throw new UnprocessableWinResult('Win result is not suit for calculation win sum');
    }
}
