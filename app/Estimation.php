<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    protected $fillable = ['amount', 'start_date', 'end_date', 'day', 'category_id', 'currency_id'];

    public function getDates()
    {
        return ['start_date', 'end_date', 'created_at', 'updated_at'];
    }

    public function getStartDateFormattedAttribute($value)
    {
        if ($this->start_date) {
            return $this->start_date->format('Y/m/d');
        }

        return null;
    }

    public function getEndDateFormattedAttribute($value)
    {
        if ($this->end_date) {
            return $this->end_date->format('Y/m/d');
        }

        return null;
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
}
