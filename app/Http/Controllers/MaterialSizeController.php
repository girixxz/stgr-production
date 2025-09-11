<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialSize;

class MaterialSizeController extends Controller
{

    public function store(Request $request)
    {
        // simpan state modal
        session()->flash('openModal', 'addSize');

        $validated = $request->validateWithBag('addSize', [
            'size_name' => 'required|max:255|unique:material_sizes,size_name',
        ]);

        MaterialSize::create($validated);

        return redirect()->route('owner.manage-data.products.index')
            ->with('success_add', 'Size added successfully.');
    }

    public function update(Request $request, MaterialSize $materialSize)
    {
        // supaya modal edit tetap kebuka kalau error
        session()->flash('openModal', 'editSize');
        session()->flash('editSizeId',  $materialSize->id);

        $validated = $request->validateWithBag('editSize', [
            'size_name' => 'required|max:255|unique:material_sizes,size_name,' . $materialSize->id,
        ]);

        $materialSize->update(array_filter($validated));

        return redirect()->route('owner.manage-data.products.index')
            ->with('success_edit', 'Size updated successfully.');
    }

    public function destroy(MaterialSize $materialSize)
    {
        $materialSize->delete();

        return redirect()->route('owner.manage-data.products.index')
            ->with('success', 'Size Category deleted successfully.');
    }
}
