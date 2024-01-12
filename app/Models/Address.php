<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['bussinesses_id', 'city', 'district', 'valige'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
