<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [ProductController::class, 'search'])->name('search');

Route::get('language/{language}', [HomeController::class, 'changeLanguage'])->name('language');

Route::get('products/{slug}.html', [ProductController::class, 'show'])->name('products.show');

Route::get('categories/{slug}.html', [CategoryController::class, 'show'])->name('categories.show');

Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin', 'auth']], function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('categories', Admin\CategoryController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'store' => 'admin.categories.store',
            'edit' => 'admin.categories.edit',
            'update' => 'admin.categories.update',
            'destroy' => 'admin.categories.destroy',
        ]);
    Route::resource('products', Admin\ProductController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ]);
    Route::resource('users', Admin\UserController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);
    Route::patch('user/{id}/block', [Admin\UserController::class, 'blockUser'])
        ->name('admin.users.block');
    Route::patch('user/{id}/unblock', [Admin\UserController::class, 'unblockUser'])
        ->name('admin.users.unblock');
    Route::get('order-manager', [Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('order-manager/view-order/{id}', [Admin\OrderController::class, 'viewOrder'])
        ->name('admin.orders.viewOrder');
    Route::post('order-manager/view-order/{id}', [Admin\OrderController::class, 'update'])
        ->name('admin.orders.update');
});

Route::group(['middleware' => ['auth']], function () {
    // Cart
    Route::get('cart', [CartController::class, 'index'])->name('cart');
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('cart/order', [CartController::class, 'order'])->name('cart.order');

    // Profile
    Route::get('profile', [ProfileController::class, 'editProfile'])->name('profile');
    Route::patch('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('profile/change-password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('profile/change-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    // User
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('user/purchase', [UserController::class, 'purchase'])->name('user.purchase');
    Route::get('user/order/{id}', [UserController::class, 'orderDetail'])->name('user.purchase.details');
    Route::post('user/order/{id}/cancel', [UserController::class, 'orderCancel'])->name('user.purchase.cancel');
    //Rating
    Route::get('user/rating', [RatingController::class, 'index'])->name('user.rating');
    Route::get('user/rating/{order_id}', [RatingController::class, 'showAllOrder'])->name('user.rating.view');
    Route::post('user/rating/{order_items_id}', [RatingController::class, 'addRating'])->name('user.rating.send');
});
