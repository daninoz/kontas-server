<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SimpleExpense extends Model
{
    protected $fillable = ['amount', 'category_id'];

    public function expenses()
    {
        return $this->morphMany('App\Expense', 'type');
    }
}
