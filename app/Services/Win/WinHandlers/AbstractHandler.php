<?php

namespace App\Services\Win\WinHandlers;

abstract class AbstractHandler implements WinCalculatorInterface
{
    /**
     * @var int
     */
    protected int $winResult;

    /**
     * @param int $winResult
     *
     * @return void
     */
    public function __construct(int $winResult)
    {
        $this->winResult = $winResult;
    }

    /**
     * @return int
     */
    abstract protected function getPercent(): int;

    /**
     * @return float
     */
    public function calculate(): float
    {
        $percent = $this->getPercent();

        return $this->winResult * $percent / 100;
    }
}
