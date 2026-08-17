<?php

use App\Http\Controllers\Backend\Products\CategoryController;
use App\Http\Controllers\Backend\Products\ProductController;
use App\Http\Controllers\Backend\Profile\MyProfileController;
use App\Http\Controllers\Backend\RolePermission\RolePermissionController;
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

Route::prefix('dashboard/')->name('dashboard.')->middleware(['auth', 'not-customer'])->group(function () {

    // profile routes
    Route::get('profile', [MyProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [MyProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/password', [MyProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ==== role & permission ====
    Route::prefix('users/')->name('users.')->group(function () {

        // user list (default page)
        Route::get('/', [RolePermissionController::class, 'index'])->name('index')->middleware('can:user-list');
        Route::get('search', [RolePermissionController::class, 'searchUsers'])->name('search');

        // user-ke role dewa / delete kora
        Route::put('{user}/assign-role', [RolePermissionController::class, 'assignRole'])->name('assign-role')->middleware('can:role-assign');
        Route::delete('{user}', [RolePermissionController::class, 'destroyUser'])->name('destroy')->middleware('can:user-delete');

        // ==== role routes ====
        Route::prefix('roles/')->name('roles.')->group(function () {
            Route::get('/', [RolePermissionController::class, 'roleIndex'])->name('index')->middleware('can:role-list');
            Route::get('create', [RolePermissionController::class, 'createRole'])->name('create')->middleware('can:role-create');
            Route::post('/', [RolePermissionController::class, 'storeRole'])->name('store')->middleware('can:role-create');
            Route::get('{role}/edit', [RolePermissionController::class, 'editRole'])->name('edit')->middleware('can:role-edit');
            Route::put('{role}', [RolePermissionController::class, 'updateRole'])->name('update')->middleware('can:role-edit');
            Route::delete('{role}', [RolePermissionController::class, 'destroyRole'])->name('destroy')->middleware('can:role-delete');
        });

    });

    // categories routes

    // build a URL like /dashboard/categories/red-gift-boxes/edit
    Route::prefix('categories/')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index')->middleware('can:category-list');
        Route::get('create', [CategoryController::class, 'create'])->name('create')->middleware('can:category-create');
        Route::post('/', [CategoryController::class, 'store'])->name('store')->middleware('can:category-create');
        Route::get('{category:slug}/edit', [CategoryController::class, 'edit'])->name('edit')->middleware('can:category-edit');
        Route::put('{category:slug}', [CategoryController::class, 'update'])->name('update')->middleware('can:category-edit');
        Route::delete('{category:slug}', [CategoryController::class, 'destroy'])->name('destroy')->middleware('can:category-delete');
    });

    // products routes
    // same idea: {product:slug} -> /dashboard/products/red-gift-box/edit
    Route::prefix('products/')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index')->middleware('can:product-list');
        Route::get('create', [ProductController::class, 'create'])->name('create')->middleware('can:product-create');
        Route::post('/', [ProductController::class, 'store'])->name('store')->middleware('can:product-create');
        Route::get('{product:slug}/edit', [ProductController::class, 'edit'])->name('edit')->middleware('can:product-edit');
        Route::put('{product:slug}', [ProductController::class, 'update'])->name('update')->middleware('can:product-edit');
        Route::delete('{product:slug}', [ProductController::class, 'destroy'])->name('destroy')->middleware('can:product-delete');

        // stock in / stock out (also slug-based, same product model)
        Route::get('{product:slug}/stock', [StockController::class, 'index'])->name('stock.index')->middleware('can:product-stock');
        Route::post('{product:slug}/stock', [StockController::class, 'store'])->name('stock.store')->middleware('can:product-stock');
    });
});

require __DIR__.'/auth.php';
