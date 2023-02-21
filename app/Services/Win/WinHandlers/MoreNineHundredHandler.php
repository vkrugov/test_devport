<?php

namespace App\Services\Win\WinHandlers;

final class MoreNineHundredHandler extends AbstractHandler
{
    private const PERCENT_FROM_WIN = 70;

    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->winResult > 900;
    }

    /**
     * @return int
     */
    protected function getPercent(): int
    {
        return self::PERCENT_FROM_WIN;
    }
}
