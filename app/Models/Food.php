<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'status',
    ];

    // Food item vendor (vendor is user)
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'food_id');
    }

    // For get category 
    public function category(){
        return $this->belongsTO(Category::class, 'category_id', 'id' );
    }

     public function get_user(){
        return $this->belongsTO(User::class, 'vendor_id', 'id' );
    }
}
