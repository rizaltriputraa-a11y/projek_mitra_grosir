<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPdfController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Route;

// PUBLIC
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/promo', [ProductController::class, 'promo'])->name('products.promo');
Route::get('/kontak', [WhatsAppController::class, 'contact'])->name('contact');

// GUEST
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// AUTH
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/ringkasan', [CheckoutController::class, 'review'])->name('checkout.review');
    Route::post('/pesanan/buat', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/pesanan/beli-langsung/{id}', [CheckoutController::class, 'buyNow'])->name('checkout.buyNow');

    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan/{orderNumber}/nota', [OrderPdfController::class, 'download'])->name('orders.pdf');
    Route::get('/pesanan/{orderNumber}/whatsapp', [OrderController::class, 'redirectWhatsApp'])->name('orders.whatsapp');

    
    Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

// ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
        // ============================================
    // PENCATATAN BARANG DATANG
    // ============================================
    Route::resource('goods-receipts', \App\Http\Controllers\Admin\AdminGoodsReceiptController::class);

    // ============================================
    // REKAP PENJUALAN
    // ============================================
    Route::get('sales-report', [\App\Http\Controllers\Admin\AdminSalesReportController::class, 'index'])->name('sales-report.index');
    Route::get('sales-report/export', [\App\Http\Controllers\Admin\AdminSalesReportController::class, 'exportExcel'])->name('sales-report.export');

    Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');

    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings/whatsapp', [AdminSettingController::class, 'updateWhatsapp'])->name('settings.whatsapp');
    Route::post('settings/store', [AdminSettingController::class, 'updateStore'])->name('settings.store');
});