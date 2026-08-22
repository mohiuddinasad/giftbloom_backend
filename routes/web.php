<?php

use App\Http\Controllers\Backend\Banners\BannersController;
use App\Http\Controllers\Backend\Orders\OrderController;
use App\Http\Controllers\Backend\Products\CategoryController;
use App\Http\Controllers\Backend\Products\ProductController;
use App\Http\Controllers\Backend\Profile\MyProfileController;
use App\Http\Controllers\Backend\RolePermission\RolePermissionController;
use App\Http\Controllers\Backend\Settings\SettingsController;
use App\Http\Controllers\Frontend\Cart\CartController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\ProductDetailsController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'not-customer'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// backend routes
Route::prefix('dashboard/')->name('dashboard.')->middleware(['auth', 'not-customer'])->group(function () {

    // profile routes
    Route::get('profile', [MyProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [MyProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/password', [MyProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ==== role & permission ====
    Route::prefix('users/')->name('users.')->group(function () {

        Route::get('/', [RolePermissionController::class, 'index'])->name('index')->middleware('can:user-list');
        Route::get('search', [RolePermissionController::class, 'searchUsers'])->name('search');

        Route::put('{user}/assign-role', [RolePermissionController::class, 'assignRole'])->name('assign-role')->middleware('can:role-assign');
        Route::delete('{user}', [RolePermissionController::class, 'destroyUser'])->name('destroy')->middleware('can:user-delete');

        Route::prefix('roles/')->name('roles.')->group(function () {
            Route::get('/', [RolePermissionController::class, 'roleIndex'])->name('index')->middleware('can:role-list');
            Route::get('create', [RolePermissionController::class, 'createRole'])->name('create')->middleware('can:role-create');
            Route::post('/', [RolePermissionController::class, 'storeRole'])->name('store')->middleware('can:role-create');
            Route::get('{role}/edit', [RolePermissionController::class, 'editRole'])->name('edit')->middleware('can:role-edit');
            Route::put('{role}', [RolePermissionController::class, 'updateRole'])->name('update')->middleware('can:role-edit');
            Route::delete('{role}', [RolePermissionController::class, 'destroyRole'])->name('destroy')->middleware('can:role-delete');
        });

    });

    // categories routes - {category:slug} works for BOTH top-level and
    // sub-categories (they're the same table/model, just parent_id differs)
    Route::prefix('categories/')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index')->middleware('can:category-list');
        Route::get('create', [CategoryController::class, 'create'])->name('create')->middleware('can:category-create');
        Route::post('/', [CategoryController::class, 'store'])->name('store')->middleware('can:category-create');
        Route::get('{category:slug}/edit', [CategoryController::class, 'edit'])->name('edit')->middleware('can:category-edit');
        Route::put('{category:slug}', [CategoryController::class, 'update'])->name('update')->middleware('can:category-edit');
        Route::delete('{category:slug}', [CategoryController::class, 'destroy'])->name('destroy')->middleware('can:category-delete');
    });

    // products routes
    Route::prefix('products/')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index')->middleware('can:product-list');
        Route::get('create', [ProductController::class, 'create'])->name('create')->middleware('can:product-create');
        Route::post('/', [ProductController::class, 'store'])->name('store')->middleware('can:product-create');
        Route::get('{product:slug}/edit', [ProductController::class, 'edit'])->name('edit')->middleware('can:product-edit');
        Route::put('{product:slug}', [ProductController::class, 'update'])->name('update')->middleware('can:product-edit');
        Route::delete('{product:slug}', [ProductController::class, 'destroy'])->name('destroy')->middleware('can:product-delete');
    });

    // order routes
    Route::prefix('orders/')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index')->middleware('can:order-list');
        Route::get('{order}', [OrderController::class, 'show'])->name('show')->middleware('can:order-view');
        Route::put('{order}/status', [OrderController::class, 'updateStatus'])->name('update-status')->middleware('can:order-edit');
        Route::delete('{order}', [OrderController::class, 'destroy'])->name('destroy')->middleware('can:order-delete');
    });
    // banner and vedio
    Route::prefix('banners/')->name('banners.')->group(function () {
        Route::get('/', [BannersController::class, 'index'])->name('index')->middleware('can:banner-list');
        Route::get('create', [BannersController::class, 'create'])->name('create')->middleware('can:banner-create');
        Route::post('/', [BannersController::class, 'store'])->name('store')->middleware('can:banner-create');
        Route::get('{banner}/edit', [BannersController::class, 'edit'])->name('edit')->middleware('can:banner-edit');
        Route::put('{banner}', [BannersController::class, 'update'])->name('update')->middleware('can:banner-edit');
        Route::delete('{banner}', [BannersController::class, 'destroy'])->name('destroy')->middleware('can:banner-delete');
    });

    Route::prefix('settings/')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index')->middleware('can:setting-view');
        Route::put('/', [SettingsController::class, 'update'])->name('update')->middleware('can:setting-edit');
    });
});
// frontend routes
Route::prefix('/')->name('frontend.')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('home');

    // cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('add.cart');
    Route::post('/cart/remove/{id}', [CartController::class, 'removeCart'])->name('remove.cart');
    Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');

    // product search
    Route::get('/search', [IndexController::class, 'search'])->name('search');
    // shop routes
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/category/{slug}', [ShopController::class, 'categoryWiseProduct'])->name('category-wise-product');
    Route::get('/gift-packages', [ShopController::class, 'giftPackages'])->name('gift-packages');

    // products details route
    Route::get('/product/{slug}', [ProductDetailsController::class, 'productDetails'])->name('product.details');

    Route::get('/checkout', [IndexController::class, 'checkout'])->name('checkout');

    Route::post('/checkout', [IndexController::class, 'store'])->name('checkout.store');
    Route::get('/order/success/{order_code}', [IndexController::class, 'success'])->name('order.success');

});
require __DIR__.'/auth.php';
