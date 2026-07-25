<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'phone', 'address', 'role_id', 'status', 'remember_token'];

    // Quan hệ N-1: User thuộc về 1 Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Quan hệ 1-1: User sở hữu 1 Cart
    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id');
    }

    // Quan hệ 1-N: User có nhiều Orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
