<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $fillable = [
        'order_id',
        'food_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function food(){
        return $this->belongsTo(Food::class, 'food_id');
    }
}
