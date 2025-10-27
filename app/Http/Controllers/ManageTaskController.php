<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStage;
use App\Models\ProductionStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'default');
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $dateRange = $request->input('date_range');

        // Get all production stages
        $productionStages = ProductionStage::orderBy('id')->get();

        $query = Order::with([
            'customer',
            'invoice.payments',
            'productCategory',
            'orderStages.productionStage'
        ])
        // Only show orders with WIP or Finished status
        ->whereIn('production_status', ['wip', 'finished']);

        // Apply filter based on production status
        if ($filter === 'wip') {
            $query->where('production_status', 'wip');
        } elseif ($filter === 'finished') {
            $query->where('production_status', 'finished');
        }

        // Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('invoice', function ($invoiceQuery) use ($search) {
                    $invoiceQuery->where('invoice_no', 'like', "%{$search}%");
                })
                ->orWhereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            });
        }

        // Apply date range filter
        // Default to this month if no date filter is applied
        if ($dateRange) {
            $today = now();
            switch ($dateRange) {
                case 'last_month':
                    $startDate = $today->copy()->subMonth()->startOfMonth()->format('Y-m-d');
                    $endDate = $today->copy()->subMonth()->endOfMonth()->format('Y-m-d');
                    break;
                case 'last_7_days':
                    $startDate = $today->copy()->subDays(7)->format('Y-m-d');
                    $endDate = $today->copy()->format('Y-m-d');
                    break;
                case 'yesterday':
                    $startDate = $today->copy()->subDay()->format('Y-m-d');
                    $endDate = $today->copy()->subDay()->format('Y-m-d');
                    break;
                case 'today':
                    $startDate = $today->copy()->format('Y-m-d');
                    $endDate = $today->copy()->format('Y-m-d');
                    break;
                case 'this_month':
                    $startDate = $today->copy()->startOfMonth()->format('Y-m-d');
                    $endDate = $today->copy()->endOfMonth()->format('Y-m-d');
                    break;
            }
        }
        
        // Set default to this month if no date parameters at all
        if (!$dateRange && !$startDate && !$endDate) {
            $redirect = redirect()->route('pm.manage-task', [
                'filter' => $filter,
                'search' => $search,
                'date_range' => 'this_month',
            ]);
            
            if (session()->has('message')) {
                $redirect->with('message', session('message'))
                        ->with('alert-type', session('alert-type', 'success'));
            }
            
            return $redirect;
        }

        if ($startDate) {
            $query->whereDate('order_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('order_date', '<=', $endDate);
        }

        // Sort by first payment date (DP/payment pertama kali)
        // Join with payments table and get the earliest payment date
        // DESC = data baru di atas (dari bawah ke atas)
        // Use subquery to avoid GROUP BY issues with MySQL strict mode
        $orders = $query
            ->leftJoin(DB::raw('(SELECT invoices.order_id, MIN(payments.created_at) as first_payment_date 
                FROM invoices 
                LEFT JOIN payments ON invoices.id = payments.invoice_id 
                GROUP BY invoices.order_id) as payment_dates'), 
                'orders.id', '=', 'payment_dates.order_id')
            ->select('orders.*', 'payment_dates.first_payment_date')
            ->orderByRaw('payment_dates.first_payment_date DESC, orders.created_at DESC')
            ->paginate(15)
            ->appends($request->except('page'));

        // Calculate statistics
        $stats = [
            'total_orders' => Order::whereIn('production_status', ['wip', 'finished'])->count(),
            'order_finished' => Order::where('production_status', 'finished')->count(),
        ];

        return view('pages.pm.manage-task', compact('orders', 'stats', 'productionStages', 'dateRange', 'startDate', 'endDate'));
    }

    /**
     * Update order stage dates
     */
    public function updateStage(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'stage_id' => 'required|exists:production_stages,id',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
        ]);

        DB::beginTransaction();
        try {
            // Find or create order stage
            $orderStage = OrderStage::firstOrCreate(
                [
                    'order_id' => $validated['order_id'],
                    'stage_id' => $validated['stage_id'],
                ],
                [
                    'status' => 'pending',
                ]
            );

            // Update dates
            $orderStage->update([
                'start_date' => $validated['start_date'],
                'deadline' => $validated['deadline'],
                'status' => $this->calculateStageStatus($validated['start_date'], $validated['deadline']),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stage dates updated successfully',
                'data' => $orderStage->load('productionStage')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stage dates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update stage status
     */
    public function updateStageStatus(Request $request)
    {
        $validated = $request->validate([
            'order_stage_id' => 'required|exists:order_stages,id',
            'status' => 'required|in:pending,in_progress,done',
        ]);

        try {
            $orderStage = OrderStage::findOrFail($validated['order_stage_id']);
            $orderStage->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Stage status updated successfully',
                'data' => $orderStage->load('productionStage')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stage status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate stage status based on dates and current time
     */
    private function calculateStageStatus($startDate, $deadline)
    {
        // If no dates set, status is pending
        if (!$startDate || !$deadline) {
            return 'pending';
        }

        $today = now()->startOfDay();
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($deadline)->startOfDay();

        // If current date is before start date, status is pending
        if ($today->lt($start)) {
            return 'pending';
        }

        // If current date is between start and deadline (inclusive), status is in_progress
        if ($today->between($start, $end)) {
            return 'in_progress';
        }

        // If current date is after deadline, keep as in_progress (can be manually set to done)
        return 'in_progress';
    }
}
