<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Owner\ManageUsersSalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;

/* ================= DEFAULT INDEX / LOGIN ================= */

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        switch ($user->role) {
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pm':
                return redirect()->route('pm.dashboard');
            case 'karyawan':
                return redirect()->route('karyawan.dashboard');
        }
    }
    return app(LoginController::class)->showLoginForm();
})->name('login');

Route::post('/', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/* ================= ROLE-BASED DASHBOARD ================= */
Route::middleware(['auth'])->group(function () {

    /* ---------- OWNER ---------- */
    Route::prefix('owner')->name('owner.')->middleware('role:owner')->group(function () {
        // Dashboard
        Route::get('dashboard', fn() => view('pages.owner.dashboard'))->name('dashboard');

        // Revenue
        Route::get('revenue', fn() => view('pages.owner.revenue'))->name('revenue');

        // Manage Products
        Route::get('manage-products', fn() => view('pages.owner.manage-products'))->name('manage-products');

        // Manage Work Order
        Route::get('manage-wo', fn() => view('pages.owner.manage-wo'))->name('manage-wo');

        // Page gabungan Users & Sales
        Route::get('manage-users-sales', [ManageUsersSalesController::class, 'index'])->name('manage-users-sales');

        // Users CRUD
        Route::resource('users', UserController::class)->except(['create', 'edit']);

        // Sales CRUD
        Route::resource('sales', SalesController::class)->except(['create', 'edit']);
    });

    /* ---------- ADMIN ---------- */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('dashboard', fn() => view('pages.admin.dashboard'))->name('dashboard');
        Route::get('orders', fn() => view('pages.admin.orders'))->name('orders');
        Route::get('work-orders', fn() => view('pages.admin.work-orders'))->name('work-orders');
        Route::get('payment-history', fn() => view('pages.admin.payment-history'))->name('payment-history');
        Route::get('customers', fn() => view('pages.admin.customers'))->name('customers');
    });

    /* ---------- PROJECT MANAGER ---------- */
    Route::prefix('pm')->name('pm.')->middleware('role:pm')->group(function () {
        Route::get('dashboard', fn() => view('pages.pm.dashboard'))->name('dashboard');
        Route::get('manage-task', fn() => view('pages.pm.manage-task'))->name('manage-task');
    });

    /* ---------- KARYAWAN ---------- */
    Route::prefix('karyawan')->name('karyawan.')->middleware('role:karyawan')->group(function () {
        Route::get('dashboard', fn() => view('pages.karyawan.dashboard'))->name('dashboard');
        Route::get('task', fn() => view('pages.karyawan.task'))->name('task');
    });

    /* ---------- ALL ROLE ---------- */
    Route::get('highlights', fn() => view('pages.highlights'))->name('highlights');
    Route::get('calendar', fn() => view('pages.calendar'))->name('calendar');
    Route::get('profile', fn() => view('pages.profile'))->name('profile');
});

/* ================= TEST ROUTE ================= */
Route::get('/test', fn() => 'Multi-role authentication system is working!');
