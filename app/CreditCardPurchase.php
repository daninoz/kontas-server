<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditCardPurchase extends Model
{
    public function installments()
    {
        return $this->hasMany('App\Installment');
    }

    public function expenses()
    {
        return $this->morphMany('App\Expense', 'source');
    }
}
