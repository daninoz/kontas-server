<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = ['amount', 'statement_id'];

    public function credit_card_purchase()
    {
        return $this->belongsTo('App\CreditCardPurchase');
    }

    public function statement()
    {
        return $this->belongsTo('App\Statement');
    }
}
