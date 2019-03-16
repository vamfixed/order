<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public function orderItems() {
        return $this->hasMany('App\OrderItem', 'orders_id', 'id');
    }

    public function table() {
        return $this->belongsTo('App\Table', 'tables_id', 'id');
    }
}
