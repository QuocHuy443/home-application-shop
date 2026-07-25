<?php
//Xử lý đặt hàng & lưu CSDL
namespace App\Controllers;

use App\Helpers\SessionHelper;
use App\Helpers\CsrfHelper;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as DB;

class CheckoutController extends Controller
{
    private $cartController;

    public function __construct()
    {
        SessionHelper::init();
        $this->cartController = new CartController();
    }

    // Hiển thị form thanh toán
    public function index()
    {
        $cart = $_SESSION['cart']['items'] ?? [];
        if (empty($cart)) {
            $this->redirect('/cart');
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $userId = $_SESSION['user_id'] ?? null;
        $currentUser = $userId ? User::find($userId) : null;
        if ($currentUser) {
            $currentUser->fullname = $currentUser->name;
        }

        $this->view('client/checkout', [
            'cartItems' => array_values($cart),
            'totalAmount' => $totalAmount,
            'currentUser' => $currentUser
        ], 'main');
    }

    // Xử lý Đặt hàng
    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        CsrfHelper::validate();

        $data = $_POST;

        $cart = $_SESSION['cart']['items'] ?? [];
        if (empty($cart)) {
            $this->redirect('/cart');
        }
        
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Validate dữ liệu người nhận
        if (empty($data['fullname']) || empty($data['phone']) || empty($data['address'])) {
            $this->back();
        }

        $user = SessionHelper::user();

        // Sử dụng Database Transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        try {
            // A. Tạo đơn hàng (Order)
            $order = Order::create([
                'order_code'       => 'HD' . time() . rand(10, 99),
                'user_id'          => $user['id'] ?? 1, // fallback nếu chưa login nhưng middleware đã bọc
                'shipping_name'    => $data['fullname'],
                'shipping_phone'   => $data['phone'],
                'shipping_address' => $data['address'],
                'note'             => $data['note'] ?? '',
                'total_amount'     => $totalAmount,
                'status'           => 'pending'
            ]);

            // B. Lưu từng món hàng vào OrderItem & Trừ tồn kho
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'price'      => $item['price'],
                    'quantity'   => $item['quantity']
                    // 'total' bỏ đi vì db không có
                ]);

                // Trừ số lượng tồn kho của sản phẩm
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }
            
            // C. Lưu Payment
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $data['payment_method'] ?? 'cod',
                'payment_status' => 'unpaid',
            ]);

            // D. Commit Transaction (Hoàn tất lưu vào CSDL)
            DB::commit();

            // E. Xóa giỏ hàng
            $this->cartController->clear();

            // Chuyển hướng thành công (nên có trang success, tạm redirect home)
            $this->redirect('/?checkout=success');

        } catch (\Exception $e) {
            // Nếu có bất kỳ lỗi nào xảy ra -> Rollback lại toàn bộ
            DB::rollback();
            \App\Helpers\Logger::error("Checkout error: " . $e->getMessage());
            $this->back();
        }
    }
}
