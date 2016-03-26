<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name'];

    public function getDestination($id)
    {
        return $this->findOrFail($id);
    }

    public function incomes()
    {
        return $this->morphMany('App\Income', 'destination');
    }

    public function expenses()
    {
        return $this->morphMany('App\Expense', 'source');
    }
}
