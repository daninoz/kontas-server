<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = ['amount', 'category_id'];

    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }
}
