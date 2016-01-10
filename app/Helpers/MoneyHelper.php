<?php

namespace App\Helpers;

class MoneyHelper
{
    /**
     * Converts a money value to an integer
     *
     * @param $value
     *
     * @return float
     */
    public function toStoredMoney($value) {
        return round($value, 2) * 100;
    }

    /**
     * Converts to money value from an integer
     *
     * @param $value
     *
     * @return float
     */
    public function fromStoredMoney($value) {
        return round($value / 100, 2);
    }
}