<?php

namespace App\Services\Win\WinHandlers;

interface WinCalculatorInterface
{
    /**
     * @return bool
     */
    public function check(): bool;

    /**
     * @return float
     */
    public function calculate(): float;
}
