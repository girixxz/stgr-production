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
            'services'      => Service::orderBy('service_name')->get(),
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
        $request->validate([
            'payload' => 'required|json',
        ], [
            'payload.required' => 'Data order tidak boleh kosong.',
            'payload.json'     => 'Format data tidak valid.',
        ]);

        $data = json_decode($request->payload, true);

        DB::beginTransaction();

        try {
            // 1. Order utama
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

            // 2. Design Variants + Items
            if (!empty($data['design_variants'])) {
                foreach ($data['design_variants'] as $design) {
                    $variant = DesignVariant::create([
                        'order_id'    => $order->id,
                        'design_name' => $design['name'] ?? 'No Name',
                    ]);

                    if (!empty($design['sleeveVariants'])) {
                        foreach ($design['sleeveVariants'] as $sv) {
                            if (!empty($sv['rows'])) {
                                foreach ($sv['rows'] as $row) {
                                    $itemSubtotal = ($row['unitPrice'] ?? 0) * ($row['qty'] ?? 0);

                                    OrderItem::create([
                                        'order_id'    => $order->id,
                                        'design_variant_id' => $variant->id,
                                        'sleeve_id'         => $sv['sleeve'] ?? null,
                                        'size_id'           => $row['size_id'] ?? null,
                                        'quantity'          => $row['qty'] ?? 0,
                                        'unit_price'        => $row['unitPrice'] ?? 0,
                                        'subtotal'          => $itemSubtotal,
                                    ]);

                                    $totalQty += $row['qty'] ?? 0;
                                    $subtotal += $itemSubtotal;
                                }
                            }
                        }
                    }
                }
            }

            // 3. Additional Services
            if (!empty($data['additionals'])) {
                foreach ($data['additionals'] as $add) {
                    if (!empty($add['service_id']) && !empty($add['price'])) {
                        AdditionalService::create([
                            'order_id'  => $order->id,
                            'service_id' => $add['service_id'],
                            'price'     => $add['price'],
                        ]);
                        $additional += $add['price'];
                    }
                }
            }

            // 4. Update total
            $finalPrice = $subtotal + $additional - ($data['discount'] ?? 0);

            $order->update([
                'total_qty'   => $totalQty,
                'subtotal'    => $subtotal,
                'final_price' => $finalPrice,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Order berhasil dibuat!');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage()); // sementara untuk debug
            return back()->withErrors(['addOrder' => 'Gagal menyimpan order: ' . $th->getMessage()]);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
