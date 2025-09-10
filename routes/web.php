<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Owner\ManageUsersSalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\Owner\ManageProductsController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\MaterialTextureController;
use App\Http\Controllers\MaterialSleeveController;
use App\Http\Controllers\MaterialSizeController;
use App\Http\Controllers\ShippingController;

/* ================= DEFAULT INDEX / LOGIN ================= */

Route::get('/', fn() => redirect('/login'));
Route::get('/login', function () {
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

        // Manage Data
        Route::prefix('manage-data')->name('manage-data.')->group(function () {
            // Products
            Route::prefix('products')->name('products.')->group(function () {
                Route::get('/', [ManageProductsController::class, 'index'])->name('index');
                Route::resource('product-categories', ProductCategoryController::class)->except(['index', 'create', 'show', 'edit']);
                Route::resource('material-categories', MaterialCategoryController::class)->except(['index', 'create', 'show', 'edit']);
                Route::resource('material-textures', MaterialTextureController::class)->except(['index', 'create', 'show', 'edit']);
                Route::resource('material-sleeves', MaterialSleeveController::class)
                    ->parameters([
                        'material-sleeves' => 'materialSleeve'
                    ]);
                Route::resource('material-sizes', MaterialSizeController::class)->except(['index', 'create', 'show', 'edit']);
                Route::resource('shippings', ShippingController::class)->except(['index', 'create', 'show', 'edit']);
            });

            // Work Order
            Route::prefix('work-order')->name('work-order.')->group(function () {
                Route::get('/', [ManageProductsController::class, 'index'])->name('index');
            });

            // Users & Sales
            Route::prefix('users-sales')->name('users-sales.')->group(function () {
                Route::get('/', [ManageUsersSalesController::class, 'index'])->name('index');
                Route::resource('users', UserController::class)->except(['index', 'create', 'show', 'edit']);
                Route::resource('sales', SalesController::class)->except(['index', 'create', 'show', 'edit']);
            });
        });
        // Route::get('manage-products', [ManageProductsController::class, 'index'])->name('manage-products');
        // Route::resource('product-categories', ProductCategoryController::class)->except(['index', 'create', 'show', 'edit']);
        // Route::resource('material-categories', MaterialCategoryController::class)->except(['index', 'create', 'show', 'edit']);
        // Route::resource('material-textures', MaterialTextureController::class)->except(['index', 'create', 'show', 'edit']);
        // Route::resource('material-sleeves', MaterialSleeveController::class)
        //     ->parameters([
        //         'material-sleeves' => 'materialSleeve'
        //     ]);
        // Route::resource('material-sizes', MaterialSizeController::class)->except(['index', 'create', 'show', 'edit']);
        // Route::resource('shippings', ShippingController::class)->except(['index', 'create', 'show', 'edit']);

        // Manage Work Order
        Route::get('manage-wo', fn() => view('pages.owner.manage-wo'))->name('manage-wo');

        // Manage Users & Sales
        Route::get('manage-users-sales', [ManageUsersSalesController::class, 'index'])->name('manage-users-sales');
        Route::resource('users', UserController::class)->except(['index', 'create', 'show', 'edit']);
        Route::resource('sales', SalesController::class)->except(['index', 'create', 'show', 'edit']);
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
