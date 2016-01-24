<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use App\SimpleExpense;

class SimpleExpenseService
{
    protected $simple_expense;

    protected $money;

    public function __construct(SimpleExpense $simple_expense, MoneyHelper $money)
    {
        $this->simple_expense = $simple_expense;
        $this->money = $money;
    }

    public function get($amount, $category_id)
    {
        return $this->simple_expense->create([
            'amount' => $this->money->toStoredMoney($amount),
            'category_id' => $category_id,
        ]);
    }
}