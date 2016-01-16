<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    protected $fillable = ['amount', 'start_date', 'end_date', 'day', 'category_id', 'currency_id'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
}
