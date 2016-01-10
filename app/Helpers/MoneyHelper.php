<?php

namespace App\Helpers;

class MoneyHelper
{
    public function toStoredCurrency($value) {
        return round($value, 2) * 100;
    }

    public function fromStoredCurrency($value) {
        return round($value / 100, 2);
    }
}