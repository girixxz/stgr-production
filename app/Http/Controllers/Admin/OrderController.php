<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'sales'])->latest()->get();
        return view('pages.admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        // 1️⃣ Validasi dasar
        $data = $request->validate([
            'deadline'   => 'sometimes|date',
            'notes'      => 'nullable|string',
            'discount'   => 'nullable|integer|min:0',
            'design_variants' => 'array',
            'design_variants.*.id' => 'nullable|exists:design_variants,id',
            'design_variants.*.name' => 'required|string',
            'design_variants.*.items' => 'array',
            'design_variants.*.items.*.id' => 'nullable|exists:order_items,id',
            'design_variants.*.items.*.sleeve_id' => 'required|exists:material_sleeves,id',
            'design_variants.*.items.*.size_id'   => 'required|exists:material_sizes,id',
            'design_variants.*.items.*.qty'       => 'required|integer|min:1',
            'design_variants.*.items.*.unit_price' => 'required|integer|min:0',
            'additionals' => 'array',
            'additionals.*.id' => 'nullable|exists:additional_services,id',
            'additionals.*.name' => 'required|string',
            'additionals.*.price' => 'required|integer|min:0',
        ]);

        // 2️⃣ Update order utama
        $order->update([
            'deadline' => $data['deadline'] ?? $order->deadline,
            'notes'    => $data['notes'] ?? $order->notes,
            'discount' => $data['discount'] ?? $order->discount,
        ]);

        $subTotal = 0;

        // 3️⃣ Update / simpan design variants & items
        foreach ($data['design_variants'] ?? [] as $variantData) {
            $variant = isset($variantData['id'])
                ? $order->designVariants()->find($variantData['id'])
                : $order->designVariants()->create(['design_name' => $variantData['name']]);

            if ($variant) {
                $variant->update(['design_name' => $variantData['name']]);

                foreach ($variantData['items'] ?? [] as $item) {
                    $orderItem = isset($item['id'])
                        ? $variant->orderItems()->find($item['id'])
                        : $variant->orderItems()->make();

                    $subtotal = $item['qty'] * $item['unit_price'];
                    $orderItem->fill([
                        'order_id'          => $order->id,
                        'design_variant_id' => $variant->id,
                        'sleeve_id'         => $item['sleeve_id'],
                        'size_id'           => $item['size_id'],
                        'quantity'          => $item['qty'],
                        'unit_price'        => $item['unit_price'],
                        'subtotal'          => $subtotal,
                    ])->save();

                    $subTotal += $subtotal;
                }
            }
        }

        // 4️⃣ Update / simpan additionals
        $additionalTotal = 0;
        foreach ($data['additionals'] ?? [] as $add) {
            $additional = isset($add['id'])
                ? $order->additionalServices()->find($add['id'])
                : $order->additionalServices()->make();

            $additional->fill([
                'order_id'      => $order->id,
                'addition_name' => $add['name'],
                'price'         => $add['price'],
            ])->save();

            $additionalTotal += $add['price'];
        }

        // 5️⃣ Hitung final price
        $finalPrice = $subTotal + $additionalTotal - $order->discount;
        $order->update([
            'sub_total'   => $subTotal,
            'final_price' => max(0, $finalPrice),
            'total_qty'   => $order->orderItems()->sum('quantity'),
        ]);

        return back()->with('success', 'Order berhasil diperbarui dengan detail, items, dan additionals!');
    }


    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('success', 'Order berhasil dihapus');
    }
}
