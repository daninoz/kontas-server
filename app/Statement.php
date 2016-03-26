<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    protected $fillable = ['period', 'due_date', 'deadline', 'has_real_dates', 'credit_card_id'];

    public function getDates()
    {
        return ['period', 'due_date', 'deadline', 'created_at', 'updated_at'];
    }

    public function getDestination($id, $date = null)
    {
        return $this->where('credit_card_id', $id)->where('deadline', '>=', $date)
            ->where('has_real_dates', true) ->orderBy('period', 'asc')->first();
    }

    public function getCurrent($id, $date)
    {
        return $this->where('credit_card_id', $id)->where('deadline', '>=', $date)
            ->where('has_real_dates', true) ->orderBy('period', 'asc')->first();
    }

    public function getFuture($installment, $id, $date)
    {
        $future_statement_period = $this->getCurrent($id, $date)->period->addMonths($installment);

        $future_statement = $this->where('credit_card_id', $id) ->where('period', $future_statement_period)
            ->orderBy('period', 'asc')->first();

        if (!$future_statement) {
            $future_statement = $this->create([
                'period' => $future_statement_period->toDateTimeString(),
                'due_date' => $future_statement_period->addDay()->toDateTimeString(),
                'deadline' => $future_statement_period->subDays(2)->toDateTimeString(),
                'has_real_dates' => false,
                'credit_card_id' => $id
            ]);
        }

        return $future_statement;
    }

    public function credit_card()
    {
        return $this->belongsTo('App\CreditCard');
    }

    public function incomes()
    {
        return $this->morphMany('App\Income', 'destination');
    }

    public function installments()
    {
        return $this->hasMany('App\Installment');
    }
}
