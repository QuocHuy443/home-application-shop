<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'slug', 'description', 'price', 'stock', 'thumbnail', 'category_id'];

    // Quan hệ N-1: Sản phẩm thuộc 1 Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ 1-N: 1 Sản phẩm có nhiều Ảnh phụ
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
