<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AuthenticatedSessionController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\NotificationController;

Route::get('/language/{locale}', function (\Illuminate\Http\Request $request, string $locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);

    $request->session()->put('locale', $locale);

    return back();
})->whereIn('locale', ['ar', 'en'])->name('language.switch');


// Public Pages
Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{variantId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{variantId}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/products/{product:slug}', [ProductDetailController::class, 'show'])->name('products.show');
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/stripe/webhook', [CheckoutController::class, 'stripeWebhook'])->name('stripe.webhook');
Route::get('/checkout/bank-transfer/{order}', [CheckoutController::class, 'bankTransfer'])->name('checkout.bankTransfer');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');

    // Orders
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Notifications
    Route::post('/notifications/{id}/read', function (\Illuminate\Http\Request $request, $id) {
        $request->user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    })->name('notifications.read');
});

require __DIR__ . '/auth.php';

// Admin Routes

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store']);
    });

    Route::middleware(['auth:admin', 'admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class);

        Route::resource('products', ProductController::class);

        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'destroy']);
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Admin Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
    });
});
