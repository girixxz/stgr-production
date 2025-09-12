<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;

class ShippingController extends Controller
{

    public function store(Request $request)
    {
        // simpan state modal
        session()->flash('openModal', 'addShipping');

        $validated = $request->validateWithBag('addShipping', [
            'shipping_name' => 'required|max:255|unique:shippings,shipping_name',
        ]);

        Shipping::create($validated);

        return redirect()->to(route('owner.manage-data.products.index') . '#shippings')
            ->with('success_add', 'Shipping added successfully.');
    }

    public function update(Request $request, Shipping $shipping)
    {
        // supaya modal edit tetap kebuka kalau error
        session()->flash('openModal', 'editShipping');
        session()->flash('editShippingId',  $shipping->id);

        $validated = $request->validateWithBag('editShipping', [
            'shipping_name' => 'required|max:255|unique:shippings,shipping_name,' . $shipping->id,
        ]);

        $shipping->update(array_filter($validated));

        return redirect()->to(route('owner.manage-data.products.index') . '#shippings')
            ->with('success_edit', 'Shipping updated successfully.');
    }

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();

        return redirect()->to(route('owner.manage-data.products.index') . '#shippings')
            ->with('success', 'Shipping deleted successfully.');
    }
}
