<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Owner\ManageUsersSalesController;
use App\Http\Controllers\Owner\ManageProductsController;
use App\Http\Controllers\ProductCategoryController;
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
        Route::get('manage-products', [ManageProductsController::class, 'index'])->name('manage-products');

        // Product Categories CRUD
        Route::resource('product-categories', ProductCategoryController::class)
            ->except(['index', 'show', 'create', 'edit']);

        // Material Categories CRUD
        Route::post('material-categories', [ManageProductsController::class, 'storeMaterialCategory'])->name('material-categories.store');
        Route::put('material-categories/{materialCategory}', [ManageProductsController::class, 'updateMaterialCategory'])->name('material-categories.update');
        Route::delete('material-categories/{materialCategory}', [ManageProductsController::class, 'destroyMaterialCategory'])->name('material-categories.destroy');

        // Material Textures CRUD
        Route::post('material-textures', [ManageProductsController::class, 'storeMaterialTexture'])->name('material-textures.store');
        Route::put('material-textures/{materialTexture}', [ManageProductsController::class, 'updateMaterialTexture'])->name('material-textures.update');
        Route::delete('material-textures/{materialTexture}', [ManageProductsController::class, 'destroyMaterialTexture'])->name('material-textures.destroy');

        // Material Sleeves CRUD
        Route::post('material-sleeves', [ManageProductsController::class, 'storeMaterialSleeve'])->name('material-sleeves.store');
        Route::put('material-sleeves/{materialSleeve}', [ManageProductsController::class, 'updateMaterialSleeve'])->name('material-sleeves.update');
        Route::delete('material-sleeves/{materialSleeve}', [ManageProductsController::class, 'destroyMaterialSleeve'])->name('material-sleeves.destroy');

        // Material Sizes CRUD
        Route::post('material-sizes', [ManageProductsController::class, 'storeMaterialSize'])->name('material-sizes.store');
        Route::put('material-sizes/{materialSize}', [ManageProductsController::class, 'updateMaterialSize'])->name('material-sizes.update');
        Route::delete('material-sizes/{materialSize}', [ManageProductsController::class, 'destroyMaterialSize'])->name('material-sizes.destroy');

        // Shippings CRUD
        Route::post('shippings', [ManageProductsController::class, 'storeShipping'])->name('shippings.store');
        Route::put('shippings/{shipping}', [ManageProductsController::class, 'updateShipping'])->name('shippings.update');
        Route::delete('shippings/{shipping}', [ManageProductsController::class, 'destroyShipping'])->name('shippings.destroy');

        // Manage Work Order
        Route::get('manage-wo', fn() => view('pages.owner.manage-wo'))->name('manage-wo');

        // Page gabungan Users & Sales
        Route::get('manage-users-sales', [ManageUsersSalesController::class, 'index'])->name('manage-users-sales');

        // Users CRUD
        Route::resource('users', UserController::class)
            ->except(['index', 'show', 'create', 'edit']);

        // Sales CRUD
        Route::resource('sales', SalesController::class)
            ->except(['index', 'show', 'create', 'edit']);
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
Route::get('/test-modal', fn() => view('test-modal'));
