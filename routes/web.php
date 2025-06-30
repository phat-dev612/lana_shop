<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;


// Routes cho guest (chưa đăng nhập)
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Routes cho auth (đã đăng nhập)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Routes cho admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/add', [AdminController::class, 'addProduct'])->name('products.add');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}', [AdminController::class, 'showProduct'])->name('products.show');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.destroy');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/create', [AdminController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [AdminController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.destroy');
});

// Routes cho customer
Route::prefix('')->name('customer.')->group(function () {
    // Trang chủ khách hàng (có thể là trang sản phẩm)
    Route::get('/', [CustomerController::class, 'index'])->name('home');
    // Danh sách sản phẩm
    Route::get('/products', [CustomerController::class, 'products'])->name('products');
    // Xem sản phẩm theo danh mục
    Route::get('/category/{category:slug}', [CustomerController::class, 'category'])->name('category');
    // Tìm kiếm sản phẩm
    Route::get('/search', [CustomerController::class, 'search'])->name('search');
    Route::get('/product/{id}', [CustomerController::class, 'product'])->name('product');
    // Giỏ hàng
    Route::middleware('auth')->group(function () {
        Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
        Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/update', [CustomerController::class, 'updateCart'])->name('cart.update');
        Route::post('/cart/update-is-buy', [CustomerController::class, 'updateIsBuy'])->name('cart.update-is-buy');
        Route::post('/cart/remove', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
        
        // Thanh toán và đơn hàng
        Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
        Route::post('/checkout/place-order', [CustomerController::class, 'placeOrder'])->name('checkout.place-order');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [CustomerController::class, 'orderDetail'])->name('order.detail');
        
        // Thông tin cá nhân
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    });
});
