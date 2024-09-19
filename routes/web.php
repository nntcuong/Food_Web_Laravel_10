<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ProfileController;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware'=>'guest'],function(){
    Route::get('admin/login',[AdminAuthController::class,'index'])->name('login');

});

Route::group(['middleware'=>'auth'],function(){

    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::put('profile',[ProfileController::class,'updateProfile'])->name('profile.update');
    Route::put('profile/password',[ProfileController::class,'updatePassword'])->name('profile.password.update');

    Route::post('profile/avatar',[ProfileController::class,'updateAvatar'])->name('profile.avatar.update');

});
Route::get('/',[FrontendController::class,'index'])->name('home');
Route::Get('/product/{slug}',[FrontendController::class,'showProduct'])->name('product.show');
Route::get('/load-product-modal/{productId}', [FrontendController::class, 'loadProductModal'])->name('load-product-modal');

Route::post('add-to-cart',[CartController::class,'addToCart'])->name('add-to-cart');

Route::get('get-cart-products', [CartController::class, 'getCartProduct'])->name('get-cart-products');
require __DIR__.'/auth.php';
