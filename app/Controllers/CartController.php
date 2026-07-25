<?php
//Quản lý giỏ hàng trong Session
namespace App\Controllers;

use App\Helpers\SessionHelper;
use App\Helpers\CsrfHelper;
use App\Models\Product;
use App\Models\User;

class CartController extends Controller
{
    public function __construct()
    {
        SessionHelper::init();
        if (!isset($_SESSION['cart']['items'])) {
            $_SESSION['cart']['items'] = []; // Khởi tạo mảng giỏ hàng rỗng nếu chưa có
        }
    }

    // 1. Lấy danh sách sản phẩm trong giỏ kèm tính tổng tiền
    public function index()
    {
        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['auth_error'] = 'Vui lòng đăng nhập để sử dụng giỏ hàng.';
            $this->redirect('/login');
        }

        $cart = $_SESSION['cart']['items'] ?? [];
        $totalAmount = 0;

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $this->view('client/cart', [
            'cartItems' => array_values($cart),
            'totalAmount' => $totalAmount,
            'isLoggedIn' => true
        ], 'main');
    }

    // 2. Thêm sản phẩm vào giỏ hàng
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['auth_error'] = 'Vui lòng đăng nhập để sử dụng giỏ hàng.';
            $this->redirect('/login');
        }

        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        $product = Product::find($productId);

        if (!$product) {
            $this->back();
        }

        // Kiểm tra tồn kho
        if ($product->stock < $quantity) {
            $this->back();
        }

        // Nếu sản phẩm đã có trong giỏ -> Tăng số lượng
        if (isset($_SESSION['cart']['items'][$productId])) {
            $newQuantity = $_SESSION['cart']['items'][$productId]['quantity'] + $quantity;
            if ($newQuantity > $product->stock) {
                $this->back();
            }
            $_SESSION['cart']['items'][$productId]['quantity'] = $newQuantity;
        } else {
            // Nếu chưa có -> Thêm mới vào giỏ
            $_SESSION['cart']['items'][$productId] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'thumbnail'  => $product->thumbnail,
                'quantity'   => $quantity
            ];
        }

        $this->redirect('/cart');
    }

    // 3. Cập nhật số lượng sản phẩm trong giỏ
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['auth_error'] = 'Vui lòng đăng nhập để chỉnh sửa giỏ hàng.';
            $this->redirect('/login');
        }

        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($quantity <= 0) {
            return $this->removeAction($productId);
        }

        if (isset($_SESSION['cart']['items'][$productId])) {
            $product = Product::find($productId);
            if ($product && $quantity > $product->stock) {
                $this->back();
            }
            $_SESSION['cart']['items'][$productId]['quantity'] = $quantity;
        }

        $this->redirect('/cart');
    }

    // 4. Xóa 1 sản phẩm khỏi giỏ
    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['auth_error'] = 'Vui lòng đăng nhập để xóa sản phẩm khỏi giỏ hàng.';
            $this->redirect('/login');
        }

        $productId = $_POST['product_id'] ?? null;
        $this->removeAction($productId);
    }

    private function removeAction($productId)
    {
        if (isset($_SESSION['cart']['items'][$productId])) {
            unset($_SESSION['cart']['items'][$productId]);
        }
        $this->redirect('/cart');
    }

    // 5. Làm sạch giỏ hàng (sau khi thanh toán thành công)
    public function clear()
    {
        $_SESSION['cart']['items'] = [];
    }
}
