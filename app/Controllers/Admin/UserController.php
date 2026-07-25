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
        $users = User::with('role')->orderBy('id', 'DESC')->get();
        $this->view('admin/users/index', ['users' => $users], 'admin');
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