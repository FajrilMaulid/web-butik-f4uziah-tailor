<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'total_price',
        'status',
        'notes',
        'reference_image',
        'payment_status',
        'payment_code',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function relatedOrders()
    {
        return $this->hasMany(Order::class, 'payment_code', 'payment_code');
    }
}
