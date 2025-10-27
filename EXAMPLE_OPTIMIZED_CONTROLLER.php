<?php

/**
 * CONTOH OPTIMASI CONTROLLER
 * ==========================
 * 
 * File ini adalah contoh bagaimana mengoptimasi controller
 * Terapkan pattern ini ke controller lain di aplikasi
 */

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Helpers\CacheHelper;
use Illuminate\Http\Request;

class OptimizedOrderController extends Controller
{
    /**
     * ❌ BAD - N+1 Query Problem
     * Akan load customer, sales, dll satu per satu untuk setiap order
     */
    public function indexBad()
    {
        $orders = Order::paginate(15);
        
        // Setiap kali loop, akan query database lagi
        // foreach ($orders as $order) {
        //     $order->customer->name;  // Query 1
        //     $order->sales->name;      // Query 2
        //     $order->invoice->total;   // Query 3
        // }
        
        return view('orders.index', compact('orders'));
    }

    /**
     * ✅ GOOD - Eager Loading
     * Load semua relasi sekaligus dengan 1 query
     */
    public function indexGood(Request $request)
    {
        $query = Order::with([
            'customer',           // Load customer
            'sales',             // Load sales
            'invoice',           // Load invoice
            'productCategory',   // Load product category
            'materialCategory',  // Load material category
            'shipping',          // Load shipping
        ]);

        // Apply filters
        if ($filter = $request->input('filter')) {
            $query->where('production_status', $filter);
        }

        // Apply search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('customer_name', 'like', "%{$search}%");
                })
                ->orWhereHas('invoice', function ($invoiceQuery) use ($search) {
                    $invoiceQuery->where('invoice_no', 'like', "%{$search}%");
                });
            });
        }

        // Use pagination, NEVER use get() or all() for large data
        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('orders.index', compact('orders'));
    }

    /**
     * ✅ BEST - Menggunakan Cache untuk data statis
     */
    public function create()
    {
        // ❌ BAD - Query database setiap kali
        // $productCategories = ProductCategory::orderBy('product_name')->get();
        // $materialCategories = MaterialCategory::orderBy('material_name')->get();
        // ...

        // ✅ GOOD - Gunakan Cache Helper
        $data = [
            'productCategories' => CacheHelper::productCategories(),
            'materialCategories' => CacheHelper::materialCategories(),
            'materialTextures' => CacheHelper::materialTextures(),
            'materialSleeves' => CacheHelper::materialSleeves(),
            'materialSizes' => CacheHelper::materialSizes(),
            'services' => CacheHelper::services(),
            'shippings' => CacheHelper::shippings(),
            'sales' => CacheHelper::sales(),
            'provinces' => CacheHelper::provinces(),
        ];

        return view('orders.create', $data);
    }

    /**
     * ✅ GOOD - Load nested relationships dengan constraints
     */
    public function show(Order $order)
    {
        // Load all needed relationships
        $order->load([
            'customer.village.district.city.province', // Nested relationship
            'sales',
            'productCategory',
            'materialCategory',
            'materialTexture',
            'shipping',
            'invoice.payments',  // Load invoice with payments
            'orderItems' => function ($query) {
                $query->with(['designVariant', 'size', 'sleeve']);
            },
            'extraServices.service',
        ]);

        return view('orders.show', compact('order'));
    }

    /**
     * ✅ GOOD - Menggunakan select() untuk ambil kolom yang diperlukan saja
     */
    public function indexMinimal()
    {
        $orders = Order::select([
            'id',
            'order_date',
            'customer_id',
            'production_status',
            'grand_total',
        ])
        ->with([
            'customer:id,customer_name,phone',  // Select specific columns
            'invoice:id,order_id,invoice_no,status',
        ])
        ->paginate(15);

        return view('orders.minimal', compact('orders'));
    }

    /**
     * ✅ GOOD - Menggunakan chunk untuk process data besar
     * Gunakan untuk export atau batch processing
     */
    public function exportBig()
    {
        // Jangan gunakan get() untuk data besar
        // ❌ $orders = Order::all(); // Memory overflow!

        // ✅ Gunakan chunk
        Order::with('customer', 'invoice')
            ->chunk(100, function ($orders) {
                foreach ($orders as $order) {
                    // Process each order
                    // Export to CSV, PDF, etc
                }
            });
    }

    /**
     * ✅ GOOD - Cache query result untuk laporan
     */
    public function statistics()
    {
        $stats = cache()->remember('order_statistics', 3600, function () {
            return [
                'total_orders' => Order::count(),
                'total_revenue' => Order::sum('grand_total'),
                'pending_orders' => Order::where('production_status', 'pending')->count(),
                'completed_orders' => Order::where('production_status', 'finished')->count(),
            ];
        });

        return view('dashboard.statistics', compact('stats'));
    }

    /**
     * TIPS OPTIMASI LAINNYA
     * ======================
     * 
     * 1. Gunakan index di database untuk kolom yang sering di-query
     * 2. Hindari N+1 query dengan eager loading
     * 3. Cache data yang jarang berubah
     * 4. Gunakan pagination untuk data banyak
     * 5. Select hanya kolom yang diperlukan
     * 6. Gunakan whereHas dengan constraints
     * 7. Avoid using all() or get() without pagination
     * 8. Use chunk() for large datasets
     * 9. Cache query results untuk dashboard/reports
     * 10. Monitor slow queries dengan DB::listen()
     */
}
