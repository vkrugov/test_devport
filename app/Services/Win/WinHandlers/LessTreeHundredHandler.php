<?php

namespace App\Services\Win\WinHandlers;

final class LessTreeHundredHandler extends AbstractHandler
{
    private const PERCENT_FROM_WIN = 10;

    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->winResult <= 300;
    }

    /**
     * @return int
     */
    protected function getPercent(): int
    {
        return self::PERCENT_FROM_WIN;
    }
}
