<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable = ['users_id', 'bussiness_id', 'products_id', 'order_quantity', 'price'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class, 'orders_id', 'id');
    }
}
