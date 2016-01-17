<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    protected $fillable = ['period', 'due_date', 'deadline', 'has_real_dates', 'credit_card_id'];

    public function getDestination($id, $date = null)
    {
        return $this->where('credit_card_id', $id)->where('deadline', '>=', $date)
            ->orderBy('period', 'asc')->first();
    }

    public function credit_card()
    {
        return $this->belongsTo('App\CreditCard');
    }

    public function incomes()
    {
        return $this->morphMany('App\Income', 'destination');
    }
}
