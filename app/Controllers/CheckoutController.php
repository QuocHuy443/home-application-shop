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

        if (!$currentUser && SessionHelper::isLoggedIn()) {
            $currentUser = (object) SessionHelper::user();
        }

        if ($currentUser && is_object($currentUser)) {
            $currentUser->fullname = $currentUser->fullname ?? $currentUser->name ?? '';
            if (empty($currentUser->phone) && !empty($_SESSION['user_phone'])) {
                $currentUser->phone = $_SESSION['user_phone'];
            }
            if (empty($currentUser->address) && !empty($_SESSION['user_address'])) {
                $currentUser->address = $_SESSION['user_address'];
            }
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
        if (empty(trim($data['fullname'] ?? ''))) {
            $_SESSION['error'] = 'Vui lòng nhập họ và tên người nhận hàng!';
            $this->back();
            return;
        }

        if (empty(trim($data['phone'] ?? ''))) {
            $_SESSION['error'] = 'Vui lòng nhập số điện thoại người nhận hàng!';
            $this->back();
            return;
        }

        if (empty(trim($data['address'] ?? ''))) {
            $_SESSION['error'] = 'Vui lòng nhập địa chỉ nhận hàng chi tiết!';
            $this->back();
            return;
        }

        $user = SessionHelper::user();

        // Sử dụng Database Transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        try {
            // Chỉ lưu SĐT & Địa chỉ vào tài khoản User nếu trước đó trong CSDL CHƯA CÓ (ghi 1 lần)
            if (!empty($user['id'])) {
                $userModel = User::find($user['id']);
                if ($userModel) {
                    $updateProfile = [];
                    if (empty($userModel->phone) && !empty($data['phone'])) {
                        $updateProfile['phone'] = trim($data['phone']);
                    }
                    if (empty($userModel->address) && !empty($data['address'])) {
                        $updateProfile['address'] = trim($data['address']);
                    }
                    if (!empty($updateProfile)) {
                        $userModel->update($updateProfile);
                        SessionHelper::updateSessionInfo(
                            $updateProfile['phone'] ?? null, 
                            $updateProfile['address'] ?? null
                        );
                    }
                }
            }

            // A. Tạo đơn hàng (Order)
            $order = Order::create([
                'order_code'       => 'HD' . time() . rand(10, 99),
                'user_id'          => $user['id'] ?? 1,
                'shipping_name'    => trim($data['fullname']),
                'shipping_phone'   => trim($data['phone']),
                'shipping_address' => trim($data['address']),
                'note'             => trim($data['note'] ?? ''),
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

            // Chuyển hướng theo phương thức thanh toán
            $paymentMethod = $data['payment_method'] ?? 'cod';
            if ($paymentMethod === 'bank_transfer') {
                $this->redirect('/checkout/qr/' . $order->id);
            } else {
                $this->redirect('/checkout/success/' . $order->id);
            }

        } catch (\Exception $e) {
            // Nếu có bất kỳ lỗi nào xảy ra -> Rollback lại toàn bộ
            DB::rollback();
            \App\Helpers\Logger::error("Checkout error: " . $e->getMessage());
            $this->back();
        }
    }

    // Hiển thị trang Quét Mã QR Ngân Hàng
    public function showQr($id)
    {
        $order = Order::with(['items.product', 'payment'])->find($id);

        if (!$order) {
            $this->redirect('/cart');
        }

        $userId = $_SESSION['user_id'] ?? null;
        if ($order->user_id != $userId) {
            $this->redirect('/');
        }

        // Nếu đơn hàng đã được thanh toán -> sang thẳng trang success
        if ($order->payment && $order->payment->payment_status === 'paid') {
            $this->redirect('/checkout/success/' . $order->id);
        }

        // Thông tin cấu hình Ngân hàng (MB Bank)
        $bankConfig = [
            'bank_code'    => 'MB',
            'bank_name'    => 'Ngân hàng TMCP Quân Đội (MBBank)',
            'account_no'   => '803022005',
            'account_name' => 'HOME APPLIANCE SHOP',
            'template'     => 'compact2'
        ];

        $orderCode = $order->order_code;
        $amount = (int) $order->total_amount;
        $qrUrl = "https://img.vietqr.io/image/{$bankConfig['bank_code']}-{$bankConfig['account_no']}-{$bankConfig['template']}.png?amount={$amount}&addInfo=" . urlencode($orderCode) . "&accountName=" . urlencode($bankConfig['account_name']);

        $this->view('client/qr_payment', [
            'order'      => $order,
            'qrUrl'      => $qrUrl,
            'bankConfig' => $bankConfig
        ], 'main');
    }

    // Xử lý Giả lập/Xác nhận thanh toán thành công qua QR
    public function confirmQrPayment($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        CsrfHelper::validate();

        $order = Order::with('payment')->find($id);
        if (!$order) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Đơn hàng không tồn tại'], 404);
            }
            $this->redirect('/cart');
        }

        // Cập nhật trạng thái đơn hàng thành Đang xử lý & Đã thanh toán
        $order->update(['status' => 'processing']);
        if ($order->payment) {
            $order->payment->update([
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'TRANS_QR_' . time() . rand(100, 999)
            ]);
        }

        if ($this->isAjax()) {
            $this->json([
                'success'  => true,
                'message'  => 'Xác nhận thanh toán thành công!',
                'redirect' => '/checkout/success/' . $order->id
            ]);
        }

        $this->redirect('/checkout/success/' . $order->id);
    }

    // Trang Đặt hàng & Thanh toán Thành công
    public function success($id)
    {
        $order = Order::with(['items.product', 'payment'])->find($id);

        if (!$order) {
            $this->redirect('/');
        }

        $userId = $_SESSION['user_id'] ?? null;
        if ($order->user_id != $userId) {
            $this->redirect('/');
        }

        $this->view('client/checkout_success', [
            'order' => $order
        ], 'main');
    }

    private function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
