<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name'];

    public function getDestination($id, $date = null)
    {
        return $this->findOrFail($id);
    }

    public function incomes()
    {
        return $this->morphMany('App\Income', 'destination');
    }
}
