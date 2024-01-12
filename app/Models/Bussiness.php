<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class Bussiness extends Model
{
    use HasFactory, HasApiTokens, HasRoles;
    protected $fillable = ['name', 'bussiness_name', 'email', 'password', 'phone', 'delivery', 'has_user_account'];

    public function products()
    {
        return $this->hasMany(Product::class, 'bussinesses_id',);
    }
    public function address()
    {
        return $this->hasOne(Address::class, 'bussinesses_id');
    }
}
