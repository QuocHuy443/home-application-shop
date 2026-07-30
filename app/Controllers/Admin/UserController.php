<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Helpers\SessionHelper;

class UserController extends Controller
{
    public function index()
    {
        SessionHelper::init();

        // 1. Nhận dữ liệu từ bộ lọc trên Form (GET)
        $keyword = trim($_GET['keyword'] ?? '');
        $roleId  = $_GET['role_id'] ?? '';
        $status  = $_GET['status'] ?? '';

        // 2. Khởi tạo Query lấy người dùng cùng bảng Role
        $query = User::with('role');

        // 3. Lọc theo từ khóa (Tìm theo Họ tên, Email hoặc Số điện thoại)
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('email', 'LIKE', "%{$keyword}%")
                  ->orWhere('phone', 'LIKE', "%{$keyword}%");
            });
        }

        // 4. Lọc theo Vai trò (role_id)
        if ($roleId !== '' && $roleId !== null) {
            $query->where('role_id', $roleId);
        }

        // 5. Lọc theo Trạng thái (status: 0 = Đã khóa, 1 = Hoạt động)
        if ($status !== '' && $status !== null) {
            $query->where('status', (int)$status);
        }

        // 6. Lọc và phân trang kết quả (10 người dùng mỗi trang)
        $users = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();

        // 7. Lấy danh sách tất cả các Role để đổ ra Dropdown Lọc
        $roles = Role::all();

        // 8. Trả dữ liệu ra View
        $this->view('admin/users/index', [
            'users'   => $users,
            'roles'   => $roles,
            'filters' => [
                'keyword' => $keyword,
                'role_id' => $roleId,
                'status'  => $status,
            ]
        ], 'admin');
    }

    public function updateRole()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $userId = $_POST['user_id'] ?? null;
        $roleName = $_POST['role'] ?? 'customer';

        $user = User::find($userId);
        if (!$user) {
            $this->redirect('/admin/users');
            return;
        }

        $role = Role::where('name', $roleName)->first();
        $user->update([
            'role_id' => $role ? $role->id : 2,
        ]);

        $this->redirect('/admin/users');
    }

    public function toggleStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
            return;
        }

        $userId = $_POST['id'] ?? $_POST['user_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($userId) {
            $user = User::find($userId);

            if ($user) {
                // Kiểm tra không cho Admin tự khóa tài khoản của chính mình
                $currentUser = SessionHelper::user();
                $currentUserId = $currentUser ? (is_array($currentUser) ? ($currentUser['id'] ?? null) : ($currentUser->id ?? null)) : null;

                // Nếu status truyền vào rỗng thì tự đảo ngược trạng thái cũ
                $newStatus = ($status !== null) ? (int)$status : (((int)$user->status === 1) ? 0 : 1);

                if ($currentUserId && (int)$currentUserId === (int)$userId && $newStatus === 0) {
                    $this->redirect('/admin/users');
                    return;
                }

                // Cập nhật trạng thái mới
                $user->update(['status' => $newStatus]);
            }
        }

        // Chuyển hướng quay lại trang quản lý người dùng
        $this->redirect('/admin/users');
    }
}