<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function store(Request $request)
    {
        session()->flash('openModal', 'addSales');
        $validated = $request->validateWithBag('addSales', [
            'sales_name' => 'required|max:100|unique:sales,sales_name',
            'phone' => 'nullable|max:100',
        ]);

        Sale::create($validated);

        return redirect(route('owner.manage-data.users-sales.index') . '#sales')
            ->with('message', 'Sales added successfully.')
            ->with('alert-type', 'success');
    }

    public function update(Request $request, Sale $sale)
    {
        session()->flash('openModal', 'editSales');
        session()->flash('editSalesId', $sale->id);
        $validated = $request->validateWithBag('editSales', [
            'sales_name' => 'required|max:100|unique:sales,sales_name,' . $sale->id,
            'phone' => 'nullable|max:100',
        ]);

        $sale->update($validated);

        return redirect(route('owner.manage-data.users-sales.index') . '#sales')
            ->with('message', 'Sales updated successfully.')
            ->with('alert-type', 'success');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('owner.manage-data.users-sales.index')
            ->with('message', 'Sales deleted successfully.')
            ->with('alert-type', 'success');
    }
}
