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
            return $this->json(['success' => false, 'message' => 'Method not allowed'], 405);
        }

        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true) ?: [];

        $userId = $data['id'] ?? $_POST['id'] ?? null;
        $status = $data['status'] ?? $_POST['status'] ?? 1;

        $user = User::find($userId);
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'User không tồn tại'], 404);
        }

        $currentUser = SessionHelper::user();

        if ($currentUser && (int)$currentUser['id'] === (int)$userId && (int)$status === 0) {
            return $this->json([
                'success' => false, 
                'message' => 'Bạn không thể tự khóa tài khoản Admin của chính mình!'
            ], 400);
        }

        $user->update(['status' => (int)$status]);

        return $this->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
    }
}