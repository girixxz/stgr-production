<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\ProductCategory;
use App\Models\MaterialCategory;
use App\Models\MaterialTexture;
use App\Models\MaterialSleeve;
use App\Models\MaterialSize;
use App\Models\Shipping;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\AdditionalService;
use App\Models\DesignVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private function getData()
    {
        return [
            'customers'          => Customer::orderBy('name')->get(),
            'sales'              => Sales::orderBy('sales_name')->get(),
            'productCategories'  => ProductCategory::orderBy('product_name')->get(),
            'materialCategories' => MaterialCategory::orderBy('material_name')->get(),
            'materialTextures'   => MaterialTexture::orderBy('texture_name')->get(),
            'materialSleeves'    => MaterialSleeve::orderBy('sleeve_name')->get(),
            'materialSizes'      => MaterialSize::orderBy('size_name')->get(),
            'services'           => Service::orderBy('service_name')->get(),
            'shippings'          => Shipping::orderBy('shipping_name')->get(),
        ];
    }

    public function index()
    {
        return view('pages.admin.orders.index', $this->getData());
    }

    public function create()
    {
        return view('pages.admin.orders.create', $this->getData());
    }

    public function store(Request $request)
    {
        // ================== 1. VALIDASI ==================
        $request->validate([
            'payload' => 'required|json',
        ], [
            'payload.required' => 'Data order tidak boleh kosong.',
            'payload.json'     => 'Format data tidak valid.',
        ]);

        $data = json_decode($request->payload, true);

        // Validasi isi payload (biar gak cuma sekadar json)
        $validator = Validator::make($data, [
            'customer_id'         => 'required|exists:customers,id',
            'sales_id'            => 'required|exists:sales,id',
            'order_date'          => 'required|date',
            'deadline'            => 'required|date|after_or_equal:order_date',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_color'       => 'required|string|max:255',
            'material_category_id' => 'required|exists:material_categories,id',
            'material_texture_id' => 'required|exists:material_textures,id',
            'shipping_id'         => 'required|exists:shippings,id',

            // validasi design_variants
            'design_variants' => 'required|array|min:1',
            'design_variants.*.name' => 'required|string|max:255',
            'design_variants.*.sleeveVariants' => 'required|array|min:1',
            'design_variants.*.sleeveVariants.*.rows' => 'required|array|min:1',
            'design_variants.*.sleeveVariants.*.rows.*.size_id' => 'required|exists:material_sizes,id',
            'design_variants.*.sleeveVariants.*.rows.*.qty' => 'required|integer|min:0',
            'design_variants.*.sleeveVariants.*.rows.*.unitPrice' => 'required|numeric|min:0',

            // validasi additionals
            'additionals' => 'nullable|array',
            'additionals.*.service_id' => 'required_with:additionals.*.price|exists:services,id',
            'additionals.*.price' => 'required_with:additionals.*.service_id|numeric|min:0',
        ], [
            // 🟢 Custom, user-friendly messages
            'customer_id.required'          => 'Customer is required.',
            'customer_id.exists'            => 'Selected customer does not exist.',

            'sales_id.required'             => 'Salesperson is required.',
            'sales_id.exists'               => 'Selected sales person does not exist.',

            'order_date.required'           => 'Order date is required.',
            'deadline.required'             => 'Deadline is required.',
            'deadline.after_or_equal'       => 'Deadline must be the same as or after the order date.',

            'product_category_id.required'  => 'Product must be selected.',
            'product_color.required'        => 'Product color is required.',
            'material_category_id.required' => 'Material must be selected.',
            'material_texture_id.required'  => 'Material texture must be selected.',
            'shipping_id.required'          => 'Shipping option must be selected.',

            'design_variants.required'      => 'At least one design must be added.',
            'design_variants.*.name.required' => 'Design name is required.',
            'design_variants.*.sleeveVariants.required' => 'At least one sleeve variant is required.',
            'design_variants.*.sleeveVariants.*.rows.required' => 'At least one size must be added for this sleeve variant.',
            'design_variants.*.sleeveVariants.*.rows.*.size_id.required' => 'Size must be selected.',
            'design_variants.*.sleeveVariants.*.rows.*.qty.required' => 'Quantity is required.',
            'design_variants.*.sleeveVariants.*.rows.*.unitPrice.required' => 'Unit price is required.',

            'additionals.*.service_id.required_with' => 'Additional service must be selected.',
            'additionals.*.price.required_with'      => 'Additional service price is required.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'addOrder')
                ->withInput()
                ->with([
                    'message' => $validator->errors()->first(), // 🔥 ambil error pertama
                    'alert-type' => 'error'
                ]);
        }


        DB::beginTransaction();

        try {
            // ================== 2. BUAT ORDER UTAMA ==================
            $order = Order::create([
                'customer_id'         => $data['customer_id'],
                'sales_id'            => $data['sales_id'],
                'order_date'          => $data['order_date'],
                'deadline'            => $data['deadline'],
                'product_category_id' => $data['product_category_id'],
                'product_color'       => $data['product_color'],
                'material_category_id' => $data['material_category_id'],
                'material_texture_id' => $data['material_texture_id'],
                'notes'               => $data['notes'] ?? null,
                'discount'            => $data['discount'] ?? 0,
                'shipping_id'         => $data['shipping_id'] ?? null,
                'total_qty'           => 0,
                'subtotal'            => 0,
                'final_price'         => 0,
                'production_status'   => 'not_started',
            ]);

            $totalQty   = 0;
            $subtotal   = 0;
            $additional = 0;

            // ================== 3. DESIGN VARIANTS ==================
            foreach ($data['design_variants'] as $design) {
                $variant = DesignVariant::create([
                    'order_id'    => $order->id,
                    'design_name' => $design['name'],
                ]);

                foreach ($design['sleeveVariants'] as $sv) {
                    foreach ($sv['rows'] as $row) {
                        $itemSubtotal = ($row['unitPrice'] ?? 0) * ($row['qty'] ?? 0);

                        OrderItem::create([
                            'order_id'          => $order->id,
                            'design_variant_id' => $variant->id,
                            'sleeve_id'         => $sv['sleeve'] ?? null,
                            'size_id'           => $row['size_id'],
                            'quantity'          => $row['qty'],
                            'unit_price'        => $row['unitPrice'],
                            'subtotal'          => $itemSubtotal,
                        ]);

                        $totalQty += $row['qty'];
                        $subtotal += $itemSubtotal;
                    }
                }
            }

            // ================== 4. ADDITIONALS ==================
            if (!empty($data['additionals'])) {
                foreach ($data['additionals'] as $add) {
                    if (!empty($add['service_id']) && isset($add['price'])) {
                        AdditionalService::create([
                            'order_id'  => $order->id,
                            'service_id' => $add['service_id'],
                            'price'     => $add['price'],
                        ]);
                        $additional += $add['price'];
                    }
                }
            }

            // ================== 5. HITUNG FINAL ==================
            $finalPrice = $subtotal + $additional - ($data['discount'] ?? 0);

            $order->update([
                'total_qty'   => $totalQty,
                'subtotal'    => $subtotal,
                'final_price' => $finalPrice,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.orders.index')
                ->with([
                    'message' => 'Order successfully created!',
                    'alert-type' => 'success'
                ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'message' => 'Gagal menyimpan order',
                    'alert-type' => 'error'
                ]);
        }
    }
}
