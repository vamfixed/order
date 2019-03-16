<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //

    public function products() {
        return $this->belongsTo('App\Product');
    }
}
