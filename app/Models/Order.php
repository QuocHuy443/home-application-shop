<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['order_code', 'user_id', 'total_amount', 'shipping_name', 'shipping_phone', 'shipping_address', 'status', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ 1-N: Đơn hàng chứa nhiều chi tiết món ăn/đồ dùng
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Quan hệ 1-1: Đơn hàng đi kèm 1 thông tin thanh toán
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
}