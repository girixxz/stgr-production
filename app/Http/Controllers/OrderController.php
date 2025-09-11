<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\ProductCategory;
use App\Models\MaterialCategory;
use App\Models\MaterialTexture;
use App\Models\MaterialSleeve;
use App\Models\MaterialSize;
use App\Models\Shipping;
use Illuminate\Http\Request;

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
        //
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
