<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Default index route
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

    // kalau belum login, tampilkan login form
    return app(LoginController::class)->showLoginForm();
})->name('login');
Route::post('/', [LoginController::class, 'login'])->name('login.post'); // post input dan sumbit login
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // logout

// Role-based Dashboard Routes
Route::middleware(['auth'])->group(function () {
    /* ================= OWNER ================= */
    // Dashboard
    Route::get('/owner/dashboard', function () {
        return view('pages.owner.dashboard');
    })->middleware('role:owner')->name('owner.dashboard');

    // Revenue
    Route::get('/owner/revenue', function () {
        return view('pages.owner.revenue'); // resources/views/pages/owner/revenue.blade.php
    })->middleware('role:owner')->name('owner.revenue');

    // Manage Products
    Route::get('/owner/manage-products', function () {
        return view('pages.owner.manage-products'); // resources/views/pages/owner/revenue.blade.php
    })->middleware('role:owner')->name('owner.manage-products');

    // Manage Work Order
    Route::get('/owner/manage-wo', function () {
        return view('pages.owner.manage-wo'); // resources/views/pages/owner/revenue.blade.php
    })->middleware('role:owner')->name('owner.manage-wo');

    // Manage Users & Sales
    Route::get('/owner/manage-users-sales', function () {
        return view('pages.owner.manage-users-sales'); // resources/views/pages/owner/revenue.blade.php
    })->middleware('role:owner')->name('owner.manage-users-sales');

    /* ================= ADMIN ================= */
    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('pages.admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    // Orders
    Route::get('/admin/orders', function () {
        return view('pages.admin.orders');
    })->middleware('role:admin')->name('admin.orders');

    // Work Orders
    Route::get('/admin/work-orders', function () {
        return view('pages.admin.work-orders');
    })->middleware('role:admin')->name('admin.work-orders');

    // Payment History
    Route::get('/admin/payment-history', function () {
        return view('pages.admin.payment-history');
    })->middleware('role:admin')->name('admin.payment-history');

    // Customer
    Route::get('/admin/customers', function () {
        return view('pages.admin.customers');
    })->middleware('role:admin')->name('admin.customers');

    /* ================= PROJECT MANAGER ================= */
    // PM DASHBOARD
    Route::get('/pm/dashboard', function () {
        return view('pages.pm.dashboard');
    })->middleware('role:pm')->name('pm.dashboard');

    // Manage Task
    Route::get('/pm/manage-task', function () {
        return view('pages.pm.manage-task');
    })->middleware('role:pm')->name('pm.manage-task');

    /* ================= KARYAWAN ================= */
    // Karyawan Dashboard
    Route::get('/karyawan/dashboard', function () {
        return view('pages.karyawan.dashboard');
    })->middleware('role:karyawan')->name('karyawan.dashboard');

    Route::get('/karyawan/task', function () {
        return view('pages.karyawan.task');
    })->middleware('role:karyawan')->name('karyawan.task');

    /* ================= ALL ROLE ================= */
    // Highlights
    Route::get('/highlights', function () {
        return view('pages.highlights');
    })->name('highlights');

    // Calendar
    Route::get('/calendar', function () {
        return view('pages.calendar');
    })->name('calendar');

    // Calendar
    Route::get('/profile', function () {
        return view('pages.profile');
    })->name('profile');
});

// Additional routes can be added here as needed
Route::get('/test', function () {
    return 'Multi-role authentication system is working!';
});
