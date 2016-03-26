<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function purchase_items()
    {
        return $this->hasMany('App\PurchaseItem');
    }

    public function expenses()
    {
        return $this->morphMany('App\Expense', 'type');
    }
}
