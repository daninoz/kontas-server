<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use App\Purchase;

class PurchaseService
{
    protected $purchase;

    protected $money;

    public function __construct(Purchase $purchase, MoneyHelper $money)
    {
        $this->purchase = $purchase;
        $this->money = $money;
    }

    public function get($items)
    {
        $purchase = $this->purchase->newInstance();
        $purchase->save();

        foreach ($items as $item) {
            $purchase->purchase_items()->create([
                'amount' => $this->money->toStoredMoney($item['amount']),
                'category_id' => $item['category_id'],
            ]);
        }

        return $purchase;
    }
}