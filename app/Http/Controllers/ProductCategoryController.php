<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{

    public function store(Request $request)
    {
        // simpan state modal
        session()->flash('openModal', 'addProduct');

        $validated = $request->validateWithBag('addProduct', [
            'product_name' => 'required|max:255|unique:product_categories,product_name',
        ]);

        ProductCategory::create($validated);

        return redirect()->route('owner.manage-data.products.index')
            ->with('success_add', 'Product added successfully.');
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        // supaya modal edit tetap kebuka kalau error
        session()->flash('openModal', 'editProduct');
        session()->flash('editProductId',  $productCategory->id);

        $validated = $request->validateWithBag('editProduct', [
            'product_name' => 'required|max:255|unique:product_categories,product_name,' . $productCategory->id,
        ]);

        $productCategory->update(array_filter($validated));

        return redirect()->route('owner.manage-data.products.index')
            ->with('success_edit', 'Product updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return redirect()->route('owner.manage-data.products.index')
            ->with('success', 'Product Category deleted successfully.');
    }
}
