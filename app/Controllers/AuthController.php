<?php
// Xử lý logic Đăng ký, Đăng nhập, Đăng xuất
namespace App\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Helpers\SessionHelper;
use App\Helpers\CsrfHelper;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (SessionHelper::isLoggedIn()) {
            $this->redirect('/');
        }
        $error = $_SESSION['auth_error'] ?? '';
        unset($_SESSION['auth_error']);
        $this->view('auth/login', ['error' => $error], 'main');
    }
    
    public function showRegister()
    {
        if (SessionHelper::isLoggedIn()) {
            $this->redirect('/');
        }
        $error = $_SESSION['auth_error'] ?? '';
        unset($_SESSION['auth_error']);
        $this->view('auth/register', ['error' => $error], 'main');
    }

    // 1. Xử lý Đăng ký tài khoản
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        // CsrfHelper::validate();
        $data = $_POST;
        
        $errors = [];

        // Validate dữ liệu đầu vào
        if (empty($data['fullname'])) {
            $errors[] = "Vui lòng nhập họ tên.";
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ.";
        }
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
        }
        if ($data['password'] !== ($data['confirm_password'] ?? '')) {
            $errors[] = "Mật khẩu xác nhận không khớp.";
        }

        // Kiểm tra Email đã tồn tại trong CSDL chưa
        if (empty($errors)) {
            $existingUser = User::where('email', $data['email'])->first();
            if ($existingUser) {
                $errors[] = "Email này đã được sử dụng.";
            }
        }

        // Nếu có lỗi -> Trả về danh sách lỗi
        if (!empty($errors)) {
            $_SESSION['auth_error'] = implode(' ', $errors);
            $this->redirect('/register');
        }

        // Lấy role_id của 'customer' mặc định
        $customerRole = Role::where('name', 'customer')->first();

        // Tạo người dùng mới (Mã hóa mật khẩu bằng BCRYPT)
        $user = User::create([
            'name'     => $data['fullname'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'phone'    => $data['phone'] ?? null,
            'address'  => $data['address'] ?? null,
            'role_id'  => $customerRole ? $customerRole->id : 2, // Mặc định role customer
        ]);

        $this->redirect('/login');
    }

    // 2. Xử lý Đăng nhập
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        // CsrfHelper::validate(); // Tùy chọn, thường login form có thể bỏ CSRF, nhưng thêm thì tốt
        $data = $_POST;
        $errors = [];

        if (empty($data['username_email'])) {
            $errors[] = "Vui lòng nhập email.";
        }
        if (empty($data['password'])) {
            $errors[] = "Vui lòng nhập mật khẩu.";
        }

        if (!empty($errors)) {
            $_SESSION['auth_error'] = implode(' ', $errors);
            $this->redirect('/login');
        }

        // Tìm user theo email
        $user = User::where('email', $data['username_email'])->first();

        // Xác thực mật khẩu đã mã hóa bằng password_verify
        if (!$user || !password_verify($data['password'], $user->password)) {
            $_SESSION['auth_error'] = 'Email hoặc mật khẩu không chính xác.';
            $this->redirect('/login');
        }

        // Đăng nhập thành công -> Lưu vào Session
        SessionHelper::login($user);

        if ($user->role && $user->role->name === 'admin') {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/');
        }
    }

    // 3. Xử lý Đăng xuất
    public function logout()
    {
        SessionHelper::logout();
        $this->redirect('/');
    }
}
