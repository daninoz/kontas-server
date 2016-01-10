<?php

namespace App\Helpers;

class MoneyHelper
{
    /**
     * Converts a currency to an integer
     *
     * @param $value
     *
     * @return float
     */
    public function toStoredCurrency($value) {
        return round($value, 2) * 100;
    }

    /**
     * Converts a currency from an integer
     *
     * @param $value
     *
     * @return float
     */
    public function fromStoredCurrency($value) {
        return round($value / 100, 2);
    }
}