<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\ProductCategory;
use App\Models\MaterialCategory;
use App\Models\MaterialTexture;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Models\MaterialSize;
use App\Models\MaterialSleeve;

class CreateOrderController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        $sales = Sales::orderBy('sales_name')->get();
        $products = ProductCategory::orderBy('product_name')->get();
        $materials = MaterialCategory::orderBy('material_name')->get();
        $textures = MaterialTexture::orderBy('texture_name')->get();
        $shippings = Shipping::orderBy('shipping_name')->get();
        $sizes = MaterialSize::orderBy('size_name')->get();
        $sleeves = MaterialSleeve::orderBy('sleeve_name')->get(); // 🔥

        return view('pages.admin.orders.create-order', compact(
            'customers',
            'sales',
            'products',
            'materials',
            'textures',
            'shippings',
            'sizes',
            'sleeves'
        ));
    }


    public function store(Request $request)
    {
        $payload = json_decode($request->input('payload'), true);

        // Validasi dasar
        if (!$payload) {
            return back()->withErrors(['Invalid payload']);
        }

        // 1. Simpan order
        $order = Order::create([
            'order_date' => $payload['order_date'] ?? now(),
            'deadline' => $payload['deadline'],
            'customer_id' => $payload['customer_id'],
            'sales_id' => $payload['sales_id'],
            'product_category_id' => $payload['product_category_id'],
            'product_color' => $payload['product_color'],
            'material_category_id' => $payload['material_category_id'],
            'material_texture_id' => $payload['material_texture_id'],
            'notes' => $payload['notes'] ?? null,
            'shipping_id' => $payload['shipping_id'],
            'discount' => $payload['discount'] ?? 0,
            'sub_total' => 0,
            'final_price' => 0,
        ]);

        $subTotal = 0;

        // 2. Simpan design_variants & order_items
        foreach ($payload['design_variants'] as $design) {
            $variant = $order->designVariants()->create([
                'design_name' => $design['name'],
            ]);

            foreach ($design['sleeveVariants'] as $sleeve) {
                foreach ($sleeve['rows'] as $row) {
                    $subtotal = ($row['unitPrice'] ?? 0) * ($row['qty'] ?? 1);
                    $subTotal += $subtotal;

                    $variant->orderItems()->create([
                        'order_id' => $order->id,
                        'design_variant_id' => $variant->id,
                        'sleeve_id' => $sleeve['sleeve'] ?? null,
                        'size_id' => $row['size_id'] ?? null,   // 🔥 pakai size_id, bukan size
                        'quantity' => $row['qty'] ?? 1,
                        'unit_price' => $row['unitPrice'] ?? 0,
                        'subtotal' => $subtotal,
                    ]);
                }
            }
        }

        // 3. Simpan additional_services
        foreach ($payload['additionals'] as $add) {
            $subTotal += $add['price'] ?? 0;
            $order->additionalServices()->create([
                'addition_name' => $add['name'],
                'price' => $add['price'] ?? 0,
            ]);
        }

        // 4. Update total & final price
        $order->update([
            'sub_total' => $subTotal,
            'final_price' => $subTotal - ($payload['discount'] ?? 0),
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Order berhasil dibuat!');
    }
}
