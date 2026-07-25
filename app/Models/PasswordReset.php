<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    public $timestamps = false; // Bảng này chỉ dùng created_at nên tắt updated_at
    protected $fillable = ['email', 'token', 'created_at'];
}
