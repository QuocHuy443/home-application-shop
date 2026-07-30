<?php
namespace App\Controllers;

use App\Models\Order;
use App\Helpers\SessionHelper;

class OrderController extends Controller
{
    public function index()
    {
        $currentUser = SessionHelper::user();
        $userId = is_array($currentUser) ? ($currentUser['id'] ?? null) : ($currentUser->id ?? null);

        if (!$userId) {
            $this->redirect('/login');
            return;
        }

        // Fetch orders for the current user (phần trang)
        $orders = Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        $this->view('client/orders', [
            'user' => $currentUser,
            'orders' => $orders
        ], 'main');
    }

    public function show($id)
    {
        $currentUser = SessionHelper::user();
        $userId = is_array($currentUser) ? ($currentUser['id'] ?? null) : ($currentUser->id ?? null);

        if (!$userId) {
            $this->redirect('/login');
            return;
        }

        // Fetch the order for the current user
        $order = Order::with('items.product')->where('user_id', $userId)->where('id', $id)->first();

        if (!$order) {
            $this->redirect('/profile/orders');
            return;
        }

        $this->view('client/order_detail', [
            'user' => $currentUser,
            'order' => $order
        ], 'main');
    }
}
