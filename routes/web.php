<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\BuySellController;
use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\User\TradeController;



use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\Admin\WithdrawController as AdminWithdrawController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\DmgCoinController as AdminDmgCoinController;

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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [HomeController::class, 'index'])->name('front-home');
Route::get('/terms', [HomeController::class, 'terms'])->name('front-terms');
Route::get('/about', [HomeController::class, 'about'])->name('front-about');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/verify/{token}', [AuthController::class, 'verify'])->name('verify-email');


Route::prefix('update')->group(function(){
    Route::get('/currencies', [UpdateController::class, 'getCurrencies']);
});

Route::middleware(['guest'])->group(function(){
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAction'])->name('login-action');

    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signupAction'])->name('signup-action');

    Route::get('/forgot', [AuthController::class, 'forgot'])->name('forgot');
    Route::post('/forgot', [AuthController::class, 'forgotAction'])->name('forgot-action');

    Route::get('/reset', [AuthController::class, 'reset'])->name('reset');
    Route::post('/reset', [AuthController::class, 'resetAction'])->name('reset-action');
});

Route::prefix('user')->middleware(['auth'])->group(function(){
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('user-change-password');
    Route::post('/change-password', [AuthController::class, 'changePasswordAction'])->name('user-change-password-action');
    Route::get('/update-profile', [AuthController::class, 'updateProfile'])->name('user-update-profile');
    Route::post('/update-profile', [AuthController::class, 'updateProfileAction'])->name('user-update-profile-action');
    
    Route::get('/', [DashboardController::class, 'index'])->name('user-dashboard');
    Route::get('/transactions', [BuySellController::class, 'index'])->name('user-transactions');
    Route::get('/wallets', [WalletController::class, 'wallets'])->name('user-wallets');
    Route::prefix('wallet')->group(function(){
        Route::get('/', [WalletController::class, 'index'])->name('user-wallet');
        Route::get('/deposit', [WalletController::class, 'deposit'])->name('user-deposit');
        Route::post('/deposit', [WalletController::class, 'depositAction'])->name('user-deposit-action');
        Route::get('/withdraw', [WalletController::class, 'withdraw'])->name('user-withdraw');
        Route::post('/withdraw', [WalletController::class, 'withdrawAction'])->name('user-withdraw-action');
    });

    Route::get('/message', [MessageController::class, 'index'])->name('user-message');
    Route::post('/send-message', [MessageController::class, 'send'])->name('user-send-message');
    Route::get('/get-message', [MessageController::class, 'getMessages'])->name('user-get-message');

    Route::get('/trade', [TradeController::class, 'index'])->name('user-trade');
    Route::get('/get-chart-data', [TradeController::class, 'getChartData'])->name('user-get-chart-data');
    Route::post('/trade-buy', [TradeController::class, 'buy'])->name('user-trade-buy');
    Route::post('/trade-sell', [TradeController::class, 'sell'])->name('user-trade-sell');
    Route::get('/get-buy-orders', [TradeController::class, 'getBuyOrders'])->name('user-get-buy-orders');

});

Route::prefix('admin')->group(function(){
    Route::get('/', [AdminAuthController::class, 'login'])->name('admin-login');
    Route::post('/', [AdminAuthController::class, 'loginAction'])->name('admin-login-action');
    Route::middleware(['auth:admin'])->group(function(){
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin-dashboard');
        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin-logout');
        Route::get('/admin-change-password', [AdminAuthController::class, 'changePassword'])->name('admin-change-password');
        Route::post('/admin-change-password', [AdminAuthController::class, 'changePasswordAction'])->name('admin-change-password-action');

        Route::get('/user/list', [AdminUserController::class, 'index'])->name('admin-user-list');
        Route::get('/user/list/data', [AdminUserController::class, 'data'])->name('admin-user-list-data');
        Route::get('/user/change-status/{id}/{status}', [AdminUserController::class, 'changeStatus'])->name('admin-user-change-status');
        Route::get('/user/destroy', [AdminUserController::class, 'destroy'])->name('admin-user-destroy');
        Route::get('/user/wallets/{id}', [AdminUserController::class, 'wallets'])->name('admin-user-wallets');
        Route::get('/user/memo/{id}', [AdminUserController::class, 'memo'])->name('admin-user-memo');
        Route::post('/user/memo/{id}', [AdminUserController::class, 'memoAction'])->name('admin-user-memo-action');

        Route::get('/deposit/list', [AdminDepositController::class, 'index'])->name('admin-deposit-list');
        Route::get('/deposit/list/data', [AdminDepositController::class, 'data'])->name('admin-deposit-list-data');
        Route::get('/deposit/change-status/{id}/{status}', [AdminDepositController::class, 'changeStatus'])->name('admin-deposit-change-status');
        Route::get('/deposit/destroy', [AdminDepositController::class, 'destroy'])->name('admin-deposit-destroy');

        Route::get('/withdraw/list', [AdminWithdrawController::class, 'index'])->name('admin-withdraw-list');
        Route::get('/withdraw/list/data', [AdminWithdrawController::class, 'data'])->name('admin-withdraw-list-data');
        Route::get('/withdraw/change-status/{id}/{status}', [AdminWithdrawController::class, 'changeStatus'])->name('admin-withdraw-change-status');
        Route::get('/withdraw/destroy', [AdminWithdrawController::class, 'destroy'])->name('admin-withdraw-destroy');


        Route::get('/message', [AdminMessageController::class, 'index'])->name('admin-message-list');
        Route::get('/message-details/{to_id}', [AdminMessageController::class, 'details'])->name('admin-message-details');
        Route::post('/send-message', [AdminMessageController::class, 'send'])->name('admin-send-message');
        Route::get('/get-message', [AdminMessageController::class, 'getMessages'])->name('admin-get-message');

        Route::get('/dmg-coin', [AdminDmgCoinController::class, 'index'])->name('admin-dmg-coin');
        Route::post('/dmg-coin', [AdminDmgCoinController::class, 'action'])->name('admin-dmg-coin-action');

    });
});

