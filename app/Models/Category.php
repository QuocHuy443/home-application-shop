<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'is_active'];

    // Quan hệ 1-N: 1 Danh mục có nhiều Sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
