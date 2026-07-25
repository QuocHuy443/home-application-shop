<?php
//Quản lý đơn hàng
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    // 1. Danh sách đơn hàng
    public function index()
    {
        $orders = Order::with(['user', 'items.product', 'payment'])->orderBy('id', 'DESC')->get();
        $this->view('admin/orders/index', ['orders' => $orders], 'admin');
    }

    // 2. Chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with(['user', 'items.product', 'payment'])->find($id);
        if (!$order) {
            $this->redirect('/admin/orders');
        }
        $this->view('admin/orders/show', ['order' => $order], 'admin');
    }

    // 3. Cập nhật trạng thái đơn hàng
    public function updateStatus($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $status = $_POST['status'] ?? '';
        $orderId = $id ?? ($_POST['order_id'] ?? null);
        $order = Order::find($orderId);

        if (!$order) {
            $this->back();
        }

        $validStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];
        if (in_array($status, $validStatuses)) {
            $order->update(['status' => $status]);
        }

        $this->redirect('/admin/orders');
    }
}
