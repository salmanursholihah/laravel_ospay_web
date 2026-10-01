<?php

use App\Http\Controllers\Web\AdminAgentController;
use App\Http\Controllers\Web\AdminCommissionController;
use App\Http\Controllers\Web\AdminProductController;
use App\Http\Controllers\Web\AgentController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DepositController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Blade + session)
|--------------------------------------------------------------------------
|
| Login memakai session (guard "web"). Group terautentikasi memakai
| middleware auth:sanctum: Sanctum otomatis menerima session web, jadi
| token API (mobile) dan session web memakai user table yang sama.
|
| Callback Midtrans & IAK ada di routes/api.php (publik, tanpa CSRF).
|
*/

Route::get('/', fn () => view('landing'))->name('home');

/*
|--------------------------------------------------------------------------
| GUEST
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:register')->name('register.store');

    Route::get('/register-agen', [AuthController::class, 'showRegisterAgen'])->name('register-agen');
    Route::post('/register-agen', [AuthController::class, 'registerAgen'])
        ->middleware('throttle:register')->name('register-agen.store');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED (semua role aktif)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:superadmin,agen,user'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    /*
    |----------------------------------------------------------------------
    | USER + AGEN: katalog produk & transaksi
    |----------------------------------------------------------------------
    */
    Route::middleware('role:agen,user')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::post('/transactions/{transaction}/refresh', [TransactionController::class, 'refresh'])->name('transactions.refresh');
    });

    /*
    |----------------------------------------------------------------------
    | AGEN ONLY  (/agen/...)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:agen')->prefix('agen')->name('agen.')->group(function () {
        Route::get('/wallet', [WalletController::class, 'show'])->name('wallet');

        Route::get('/deposits', [DepositController::class, 'index'])->name('deposits.index');
        Route::get('/deposits/create', [DepositController::class, 'create'])->name('deposits.create');
        Route::post('/deposits', [DepositController::class, 'store'])->name('deposits.store');
        Route::get('/deposits/{deposit}', [DepositController::class, 'show'])->name('deposits.show');

        Route::get('/sub-users', [AgentController::class, 'subUsers'])->name('sub-users.index');
        Route::get('/sub-users/create', [AgentController::class, 'createSubUser'])->name('sub-users.create');
        Route::post('/sub-users', [AgentController::class, 'storeSubUser'])->name('sub-users.store');

        Route::get('/commissions', [AgentController::class, 'commissions'])->name('commissions');
    });

    /*
    |----------------------------------------------------------------------
    | SUPERADMIN ONLY  (/admin/...)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:superadmin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/agents', [AdminAgentController::class, 'index'])->name('agents.index');
        Route::post('/agents/{user}/approve', [AdminAgentController::class, 'approve'])->name('agents.approve');
        Route::post('/agents/{user}/reject', [AdminAgentController::class, 'reject'])->name('agents.reject');
        Route::post('/agents/{user}/suspend', [AdminAgentController::class, 'suspend'])->name('agents.suspend');
        Route::post('/agents/{user}/activate', [AdminAgentController::class, 'activate'])->name('agents.activate');

        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::patch('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::post('/products/sync', [ProductController::class, 'sync'])->name('products.sync');
        Route::post('/products/import', [AdminProductController::class, 'import'])->name('products.import');

        Route::get('/commissions', [AdminCommissionController::class, 'index'])->name('commissions.index');
        Route::post('/commissions', [AdminCommissionController::class, 'store'])->name('commissions.store');
        Route::post('/commissions/backfill', [AdminCommissionController::class, 'backfill'])->name('commissions.backfill');
        Route::post('/commissions/payout-all', [AdminCommissionController::class, 'payoutAll'])->name('commissions.payout-all');
        Route::post('/commissions/{commission}/payout', [AdminCommissionController::class, 'payout'])->name('commissions.payout');
        Route::patch('/commissions/{rule}', [AdminCommissionController::class, 'update'])->name('commissions.update');
        Route::delete('/commissions/{rule}', [AdminCommissionController::class, 'destroy'])->name('commissions.destroy');
    });
});