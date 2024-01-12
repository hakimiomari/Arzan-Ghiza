<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'bussinesses_id',
        'tittle',
        'description',
        'price',
        'discount_price',
        'quantity',
        'image',
        'expires_date'
    ];

    public function user()
    {
        return $this->belongsTo(Bussiness::class, 'bussinesses_id', 'id');
    }
    public function address()
    {
        return $this->belongsTo(Address::class, 'bussinesses_id', 'bussinesses_id');
    }
    public function orders()
    {
        return $this->belongsTo(Order::class, 'id', 'products_id');
    }
}
