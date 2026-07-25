<?php

namespace App\Helpers;

class View 
{
    /**
     * Hàm render giao diện HTML
     * @param string $view Tên file view (VD: 'client/home')
     * @param array $data Dữ liệu truyền sang View
     * @param string $layout Tên layout chung (VD: 'main' hoặc 'admin')
     */
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // 1. Trích xuất mảng $data thành các biến riêng biệt
        extract($data);

        // 2. Định nghĩa đường dẫn file view
        $viewPath = __DIR__ . '/../../app/Views/' . $view . '.php';

        // 3. Đường dẫn file layout chính
        $layoutPath = __DIR__ . '/../../app/Views/layouts/' . $layout . '.php';

        // 4. Kiểm tra nếu file view tồn tại thì mới nhúng layout
        if (file_exists($layoutPath)) {
            require_once $layoutPath;
        } else {
            echo "Lỗi: Không tìm thấy giao diện " . $layoutPath;
        }
    }
}