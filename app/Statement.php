<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    protected $fillable = ['period', 'due_date', 'deadline', 'has_real_dates', 'credit_card_id'];

    public function credit_card()
    {
        return $this->belongsTo('App\CreditCard');
    }
}
