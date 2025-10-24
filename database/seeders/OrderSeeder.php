<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DesignVariant;
use App\Models\ExtraService;
use App\Models\Invoice;
use App\Models\MaterialCategory;
use App\Models\MaterialSize;
use App\Models\MaterialSleeve;
use App\Models\MaterialTexture;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductCategory;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Shipping;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing orders data (disable foreign key checks temporarily)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Order::truncate();
        Invoice::truncate();
        Payment::truncate();
        OrderItem::truncate();
        DesignVariant::truncate();
        ExtraService::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Get required data
        $customers = Customer::all();
        $sales = Sale::all();
        $products = ProductCategory::all();
        $materials = MaterialCategory::all();
        $textures = MaterialTexture::all();
        $sleeves = MaterialSleeve::all();
        $sizes = MaterialSize::all();
        $services = Service::all();
        $shippings = Shipping::all();

        if ($customers->isEmpty() || $sales->isEmpty() || $products->isEmpty()) {
            $this->command->error('Please run CustomerSeeder, SaleSeeder, and ProductSeeder first!');
            return;
        }

        // Create 50 orders with PENDING status (minimal 20)
        for ($i = 1; $i <= 50; $i++) {
            $customer = $customers->random();
            $sale = $sales->random();
            $product = $products->random();
            $material = $materials->random();
            $texture = $textures->random();
            $shipping = $shippings->random();

            // Random priority (80% normal, 20% high)
            $priority = rand(1, 10) <= 2 ? 'high' : 'normal';

            // Random dates (last 30 days for order_date, +7 to +14 days for deadline)
            $orderDate = now()->subDays(rand(0, 30));
            $deadline = $orderDate->copy()->addDays(rand(7, 14));

            // Random product colors
            $colors = ['Red', 'Blue', 'Black', 'White', 'Green', 'Yellow', 'Navy', 'Maroon', 'Grey', 'Purple'];
            $productColor = $colors[array_rand($colors)];

            // Random totals for order
            $totalQty = rand(10, 100);
            $subtotal = rand(50, 500) * 10000; // 500k - 5jt
            $discount = rand(0, 10) * 10000; // 0 - 100k
            $grandTotal = $subtotal - $discount;

            // Create Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'sales_id' => $sale->id,
                'product_category_id' => $product->id,
                'product_color' => $productColor,
                'material_category_id' => $material->id,
                'material_texture_id' => $texture->id,
                'shipping_id' => $shipping->id,
                'order_date' => $orderDate,
                'deadline' => $deadline,
                'total_qty' => $totalQty,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'production_status' => 'pending',
                'priority' => $priority,
                'notes' => $priority === 'high' ? 'Urgent order - High priority' : null,
            ]);

            // Create Invoice
            $invoiceNo = 'INV-STGR-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            // Use grand_total from order as total_bill
            $totalBill = $grandTotal;
            
            // Random payment (50% DP, 30% paid, 20% unpaid)
            $paymentRandom = rand(1, 10);
            if ($paymentRandom <= 5) {
                // DP (paid 30-50%)
                $amountPaid = $totalBill * rand(30, 50) / 100;
                $paymentStatus = 'dp';
            } elseif ($paymentRandom <= 8) {
                // Paid (100%)
                $amountPaid = $totalBill;
                $paymentStatus = 'paid';
            } else {
                // Unpaid (0%)
                $amountPaid = 0;
                $paymentStatus = 'unpaid';
            }

            $amountDue = $totalBill - $amountPaid;

            $invoice = Invoice::create([
                'order_id' => $order->id,
                'invoice_no' => $invoiceNo,
                'total_bill' => $totalBill,
                'amount_paid' => $amountPaid,
                'amount_due' => $amountDue,
                'status' => $paymentStatus,
            ]);

            // Create Payment if there's payment
            if ($amountPaid > 0) {
                // Determine payment type based on status
                if ($paymentStatus === 'paid') {
                    $paymentType = 'full_payment';
                } else {
                    $paymentType = 'dp';
                }

                Payment::create([
                    'invoice_id' => $invoice->id,
                    'paid_at' => $orderDate->copy()->addDays(rand(0, 2)),
                    'payment_method' => ['cash', 'tranfer'][rand(0, 1)], // Note: 'tranfer' typo in migration
                    'payment_type' => $paymentType,
                    'amount' => $amountPaid,
                ]);
            }

            // Create Order Items (1-3 items per order)
            $itemCount = rand(1, 3);
            for ($j = 0; $j < $itemCount; $j++) {
                $size = $sizes->random();
                $sleeve = $sleeves->random();
                $qty = rand(5, 50);
                $unitPrice = rand(20, 100) * 1000;
                $subtotal = $qty * $unitPrice;

                // Create Design Variant
                $designVariant = DesignVariant::create([
                    'order_id' => $order->id,
                    'design_name' => 'Design ' . chr(65 + $j), // Design A, B, C
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'design_variant_id' => $designVariant->id,
                    'size_id' => $size->id,
                    'sleeve_id' => $sleeve->id,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }

            // Add Extra Services (0-2 services)
            $serviceCount = rand(0, 2);
            if ($serviceCount > 0 && $services->isNotEmpty()) {
                $selectedServices = $services->random(min($serviceCount, $services->count()));
                foreach ($selectedServices as $service) {
                    ExtraService::create([
                        'order_id' => $order->id,
                        'service_id' => $service->id,
                        'price' => rand(5, 20) * 10000,
                    ]);
                }
            }

            $this->command->info("Created order #{$i}: {$invoiceNo} ({$priority}) - Status: pending");
        }

        $this->command->info('✅ Successfully created 50 PENDING orders with invoices and items!');
    }
}
