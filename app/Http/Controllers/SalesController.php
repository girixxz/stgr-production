<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function store(Request $request)
    {
        session()->flash('openModal', 'addSales');
        $validated = $request->validateWithBag('addSales', [
            'sales_name' => 'required|max:100|unique:sales,sales_name',
            'phone_number' => 'nullable|max:100',
        ]);

        Sales::create($validated);

        return redirect()->route('owner.manage-data.users-sales.index')
            ->with('success_add_sales', 'Sales added successfully.');
    }

    public function update(Request $request, Sales $sale)
    {
        session()->flash('openModal', 'editSales');
        session()->flash('editSalesId', $sale->id);
        $validated = $request->validateWithBag('editSales', [
            'sales_name' => 'required|max:100|unique:sales,sales_name,' . $sale->id,
            'phone_number' => 'nullable|max:100',
        ]);

        $sale->update($validated);

        return redirect()->route('owner.manage-data.users-sales.index')
            ->with('success_edit_sales', 'Sales updated successfully.');
    }

    public function destroy(Sales $sale)
    {
        $sale->delete();

        return redirect()->route('owner.manage-data.users-sales.index')
            ->with('success', 'Sales deleted successfully.');
    }
}
