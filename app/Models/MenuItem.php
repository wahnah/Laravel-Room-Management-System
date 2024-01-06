<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['name', 'price', 'image'];

    // Define relationships if needed
    // For example, if you want to track orders for each menu item
    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot(['quantity', 'item_total'])
            ->withTimestamps();
    }
}

