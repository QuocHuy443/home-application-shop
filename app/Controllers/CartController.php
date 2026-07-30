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

    // Hàm phụ hỗ trợ phản hồi AJAX JSON hoặc Redirect
    private function respond($success, $message, $redirectUrl = '/cart')
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($isAjax || isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            
            // Tính lại tổng số lượng item trong giỏ để cập nhật Badge Header
            $cartCount = 0;
            if (isset($_SESSION['cart']['items'])) {
                foreach ($_SESSION['cart']['items'] as $item) {
                    $cartCount += (int)($item['quantity'] ?? 1);
                }
            }

            echo json_encode([
                'success'   => $success,
                'message'   => $message,
                'cartCount' => $cartCount
            ]);
            exit;
        }

        $this->redirect($redirectUrl);
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

        // Phân trang cho giỏ hàng (mỗi trang 5 sản phẩm)
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentItems = array_slice($cart, ($currentPage - 1) * $perPage, $perPage);
        $paginatedCart = new \Illuminate\Pagination\LengthAwarePaginator(
            array_values($currentItems),
            count($cart),
            $perPage,
            $currentPage,
            [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()
            ]
        );
        $paginatedCart->withQueryString();

        $this->view('client/cart', [
            'cartItems' => array_values($currentItems),
            'paginatedCart' => $paginatedCart,
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
            return $this->respond(false, 'Vui lòng đăng nhập để sử dụng giỏ hàng.', '/login');
        }

        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        $product = Product::find($productId);

        if (!$product) {
            return $this->respond(false, 'Sản phẩm không tồn tại.');
        }

        // Kiểm tra tồn kho
        if ($product->stock < $quantity) {
            return $this->respond(false, 'Số lượng tồn kho không đủ.');
        }

        // Nếu sản phẩm đã có trong giỏ -> Tăng số lượng
        if (isset($_SESSION['cart']['items'][$productId])) {
            $newQuantity = $_SESSION['cart']['items'][$productId]['quantity'] + $quantity;
            if ($newQuantity > $product->stock) {
                return $this->respond(false, 'Số lượng vượt quá tồn kho hiện có.');
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

        return $this->respond(true, 'Thêm vào giỏ hàng thành công!');
    }

    // 3. Cập nhật số lượng sản phẩm trong giỏ
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['auth_error'] = 'Vui lòng đăng nhập để chỉnh sửa giỏ hàng.';
            return $this->respond(false, 'Vui lòng đăng nhập để chỉnh sửa giỏ hàng.', '/login');
        }

        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($quantity <= 0) {
            return $this->removeAction($productId);
        }

        if (isset($_SESSION['cart']['items'][$productId])) {
            $product = Product::find($productId);
            if ($product && $quantity > $product->stock) {
                return $this->respond(false, 'Số lượng vượt quá tồn kho hiện có.');
            }
            $_SESSION['cart']['items'][$productId]['quantity'] = $quantity;
        }

        return $this->respond(true, 'Đã cập nhật số lượng giỏ hàng.');
    }

    // 4. Xóa 1 sản phẩm khỏi giỏ
    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['auth_error'] = 'Vui lòng đăng nhập để xóa sản phẩm khỏi giỏ hàng.';
            return $this->respond(false, 'Vui lòng đăng nhập.', '/login');
        }

        $productId = $_POST['product_id'] ?? null;
        $this->removeAction($productId);
    }

    private function removeAction($productId)
    {
        if (isset($_SESSION['cart']['items'][$productId])) {
            unset($_SESSION['cart']['items'][$productId]);
        }
        return $this->respond(true, 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    // 5. Làm sạch giỏ hàng (sau khi thanh toán thành công)
    public function clear()
    {
        $_SESSION['cart']['items'] = [];
    }
}