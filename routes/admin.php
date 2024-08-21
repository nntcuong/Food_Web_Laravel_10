<?php

use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Routes;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\WhyChooseUsController;
use Illuminate\Support\Facades\Route;
Route::group(['prefix'=>'admin','as'=>'admin.'],function(){
    
    Route::get('dashboard',[AdminDashboardController::class,'index'])->name('dashboard');

    Route::get('profile',[ProfileController::class,'index'])->name('profile');
    Route::put('profile',[ProfileController::class,'updateProfile'])->name('profile.update');

    Route::put('profile/password',[ProfileController::class,'updatePassWord'])->name('profile.password.update');
    Route::resource('slider',SliderController::class);
    Route::resource('why-choose-us',WhyChooseUsController::class);
    
});
