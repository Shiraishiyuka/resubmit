<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FleaController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\profileSettingController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

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

Route::match(['get', 'post'], '/', [HomeController::class, 'home'])->name('home_route');
Route::get('/mylist', [HomeController::class, 'mylist'])->name('home_mylist');




Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/two-factor', [LoginController::class, 'showTwoFactorForm'])->name('two_factor.form');
Route::post('/two-factor', [LoginController::class, 'verifyTwoFactor'])->name('two_factor.verify');


Route::get('/mypage/profile', [profileSettingController::class, 'profile_setting'])->name('profile_setting');
Route::post('/mypage/update', [profileSettingController::class, 'saveProfile'])->name('mypage.update');

Route::match(['get', 'post'], '/mypage', [MypageController::class, 'mypage'])->name('mypage');
// プロフィール画面の出品商品一覧
Route::get('/mypage?tab=sell', [MypageController::class, 'sell'])->name('mypage.sell');
// プロフィール画面の購入商品一覧
Route::get('/mypage?tab=buy', [MypageController::class, 'buy'])->name('mypage.buy');


Route::get('/sell', [SellController::class, 'sell'])->name('sell');
Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

// ユーザーがアドレス入力ページを表示するためのルート（GET）
Route::get('/address/{product_id}/edit', [AddressController::class, 'edit'])->name('address.edit');
// アドレスを更新するためのルート（PUT）
Route::middleware(['auth'])->group(function () {
    Route::match(['get', 'post'], '/address/{product_id}/update', [AddressController::class, 'update'])->name('update');

});



Route::get('/items/{id}', [ItemsController::class, 'item'])->name('items');
Route::middleware('auth')->group(function () {
    Route::post('/items/{id}/like', [ItemsController::class, 'like'])->name('items.like');
});
Route::post('/items/{id}/comment', [ItemsController::class, 'comment'])->name('product.comment');


Route::match(['get', 'post'], '/purchase/{id}', [PurchaseController::class, 'purchase'])->name('purchase');
