<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public function source()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->morphTo();
    }
}
