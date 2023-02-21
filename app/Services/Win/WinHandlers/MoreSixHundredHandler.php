<?php

namespace App\Services\Win\WinHandlers;

final class MoreSixHundredHandler extends AbstractHandler
{
    private const PERCENT_FROM_WIN = 50;

    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->winResult > 600;
    }

    /**
     * @return int
     */
    protected function getPercent(): int
    {
        return self::PERCENT_FROM_WIN;
    }
}
