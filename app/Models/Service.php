<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'price_per_unit',
        'description',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'price_per_unit' => 'decimal:2'];

    
    protected

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function orderCount()
    {
        return $this->orders()->whereNotIn('status', ['canceled'])->count();
    }
}
