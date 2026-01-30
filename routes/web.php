<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BannerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', AdminProductController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('customers', CustomerController::class);
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::resource('banners', BannerController::class);
        
        // Product Images
        Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::post('/products/{product}/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.primary');
        Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Account Routes
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account.index');
    Route::get('/account/orders', [App\Http\Controllers\AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order}', [App\Http\Controllers\AccountController::class, 'showOrder'])->name('account.orders.show');
});

require __DIR__.'/auth.php';

// Fallback route for storage files (since symlink is disabled)
Route::get('storage/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('filename', '.*');


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
