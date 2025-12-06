<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'employee_id',
        'employee_code',
        'vendor_id',
        'team_id',
        'total_amount',
        'payment_type',
        'payment_status',
        'order_status',
        'note',
    ];

    // Employee who placed the order
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Vendor who sell the food
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Assistant assigned (Food Delivery man)
    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    // Order items
    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
}
