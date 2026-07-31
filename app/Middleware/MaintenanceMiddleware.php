<?php

namespace App\Middleware;

use App\Helpers\SessionHelper;
use App\Models\Setting;
use App\Models\User;

class MaintenanceMiddleware
{
    public static function handle()
    {
        // 1. Đảm bảo Session luôn được khởi chạy
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isMaintenance = false;

        // 2. Lấy giá trị trạng thái bảo trì từ CSDL (Quét toàn bộ tên cột phổ biến)
        try {
            $setting = Setting::where('key', 'maintenance_mode')
                ->orWhere('setting_key', 'maintenance_mode')
                ->orWhere('name', 'maintenance_mode')
                ->first();

            if ($setting) {
                $val = $setting->value ?? $setting->setting_value ?? $setting->val ?? $setting->status ?? 0;
                $isMaintenance = ((int)$val === 1 || $val === '1' || $val === true || $val === 'true');
            }
        } catch (\Exception $e) {
            $isMaintenance = false;
        }

        // =========================================================================
        // MẸO TEST: Nếu muốn ép hiện trang bảo trì ngay lập tức để test giao diện,
        // Tuấn chỉ cần bỏ dấu // ở dòng dưới đây:
        // =========================================================================
        //      $isMaintenance = true;

        // 3. Nếu hệ thống đang ở chế độ BẢO TRÌ
        if ($isMaintenance) {

            // Lấy dữ liệu user từ Session (thử cả SessionHelper lẫn $_SESSION trực tiếp)
            $currentUser = SessionHelper::user() ?? ($_SESSION['user'] ?? null);
            $isAdmin = false;

            if ($currentUser) {
                // Lấy ID user
                $userId = is_array($currentUser) ? ($currentUser['id'] ?? null) : ($currentUser->id ?? null);

                // Kiểm tra sơ bộ từ Session
                $roleIdFromSession = is_array($currentUser) ? ($currentUser['role_id'] ?? null) : ($currentUser->role_id ?? null);
                if ((int)$roleIdFromSession === 1) {
                    $isAdmin = true;
                }

                // Nếu chưa xác định được từ Session, truy vấn trực tiếp DB để chắc chắn
                if (!$isAdmin && $userId) {
                    try {
                        $dbUser = User::with('role')->find($userId);
                        if ($dbUser) {
                            $roleName = strtolower($dbUser->role->name ?? '');
                            $roleId = (int)($dbUser->role_id ?? 0);

                            if ($roleId === 1 || $roleName === 'admin') {
                                $isAdmin = true;
                            }
                        }
                    } catch (\Exception $e) {
                        $isAdmin = false;
                    }
                }
            }

            // 4. NẾU KHÔNG PHẢI ADMIN -> CHẶN NGAY VÀ XUẤT MÀN HÌNH BẢO TRÌ
            if (!$isAdmin) {
                http_response_code(503);

                // Đường dẫn tới View giao diện bảo trì
                $viewPath = __DIR__ . '/../Views/errors/maintenance.php';
                if (!file_exists($viewPath)) {
                    $viewPath = __DIR__ . '/../../app/Views/errors/maintenance.php';
                }

                if (file_exists($viewPath)) {
                    require_once $viewPath;
                } else {
                    // HTML giao diện dự phòng
                    echo '<!DOCTYPE html>
                    <html lang="vi">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Hệ Thống Đang Bảo Trì - HomeApp Shop</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    </head>
                    <body class="bg-light d-flex align-items-center min-vh-100">
                        <div class="container text-center py-5">
                            <div class="card border-0 shadow-lg rounded-4 p-5 mx-auto bg-white" style="max-width: 550px;">
                                <div class="mb-4 text-warning">
                                    <i class="fa-solid fa-screwdriver-wrench fa-5x"></i>
                                </div>
                                <h2 class="fw-bold text-dark mb-3">Hệ Thống Đang Bảo Trì</h2>
                                <p class="text-muted mb-4">
                                    HomeApp Shop đang tiến hành nâng cấp dịch vụ để mang lại trải nghiệm tốt nhất. Vui lòng quay lại sau ít phút!
                                </p>
                                <div class="border-top pt-3 text-muted small">
                                    Hotline hỗ trợ: <strong>1900 8888</strong> | Email: <strong>support@homeapp.vn</strong>
                                </div>
                            </div>
                        </div>
                    </body>
                    </html>';
                }
                exit(); // Ngắt toàn bộ tiến trình PHP ngay tại đây
            }
        }
    }
}