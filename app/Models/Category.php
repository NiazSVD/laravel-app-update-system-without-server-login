<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'image', 'slug', 'status', 'start_time', 'end_time'];


    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
