<?php

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\AuthController;

use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Controllers\Admin\OrderController as AdminOrderController;
use App\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Controllers\Admin\UserController as AdminUserController;
use App\Controllers\Admin\SettingController as AdminSettingController;

/** @var \App\Core\Router $router */

/*
|--------------------------------------------------------------------------
| Trang người dùng (Gắn Middleware 'maintenance' kiểm tra bảo trì)
|--------------------------------------------------------------------------
*/
$router->group('', ['maintenance'], function ($router) {
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/products', [ProductController::class, 'index']);
    $router->get('/products/detail', [ProductController::class, 'show']); // Uses ?slug=... as per views
});

/*
|--------------------------------------------------------------------------
| Giỏ hàng
|--------------------------------------------------------------------------
*/
$router->get('/cart', [CartController::class, 'index']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->post('/cart/remove', [CartController::class, 'remove']);
$router->post('/cart/clear', [CartController::class, 'clear']);

/*
|--------------------------------------------------------------------------
| Thanh toán
|--------------------------------------------------------------------------
*/
$router->get('/checkout', [CheckoutController::class, 'index'], ['auth']);
$router->post('/checkout/process', [CheckoutController::class, 'processCheckout'], ['auth']);
$router->get('/checkout/qr/{id}', [CheckoutController::class, 'showQr'], ['auth']);
$router->post('/checkout/confirm-qr/{id}', [CheckoutController::class, 'confirmQrPayment'], ['auth']);
$router->get('/checkout/success/{id}', [CheckoutController::class, 'success'], ['auth']);

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
$router->group('/admin', ['admin'], function ($router) {
    $router->get('/dashboard', [AdminDashboardController::class, 'index']);

    // Admin Profile (Hồ sơ cá nhân Admin)
    $router->get('/profile', [AdminDashboardController::class, 'profile']);
    $router->post('/profile/update', [AdminDashboardController::class, 'updateProfile']);

    // Products
    $router->get('/products', [AdminProductController::class, 'index']);
    $router->get('/products/create', [AdminProductController::class, 'create']);
    $router->post('/products/store', [AdminProductController::class, 'store']);
    $router->get('/products/edit/{id}', [AdminProductController::class, 'edit']);
    $router->post('/products/update/{id}', [AdminProductController::class, 'update']);
    $router->post('/products/delete/{id}', [AdminProductController::class, 'destroy']);
    $router->post('/products/save', [AdminProductController::class, 'store']);

    // Categories
    $router->get('/categories', [AdminCategoryController::class, 'index']);
    $router->post('/categories/store', [AdminCategoryController::class, 'store']);
    $router->post('/categories/update/{id}', [AdminCategoryController::class, 'update']);
    $router->post('/categories/delete/{id}', [AdminCategoryController::class, 'destroy']);
    $router->post('/categories/save', [AdminCategoryController::class, 'store']);

    // Orders
    $router->get('/orders', [AdminOrderController::class, 'index']);
    $router->get('/orders/show/{id}', [AdminOrderController::class, 'show']);
    $router->post('/orders/update-status/{id}', [AdminOrderController::class, 'updateStatus']);
    $router->post('/orders/update-status', [AdminOrderController::class, 'updateStatus']);

    // Users
    $router->get('/users', [AdminUserController::class, 'index']);
    $router->post('/users/update-role', [AdminUserController::class, 'updateRole']);
    $router->post('/users/toggle-status', [AdminUserController::class, 'toggleStatus']);

    // Settings (Cài Đặt & Cấu Hình Hệ Thống)
    $router->get('/settings', [AdminSettingController::class, 'index']);
    $router->post('/settings/update', [AdminSettingController::class, 'update']);
});