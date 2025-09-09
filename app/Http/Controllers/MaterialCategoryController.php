<?php

namespace App\Http\Controllers;

use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    public function store(Request $request)
    {
        // simpan state modal
        session()->flash('openModal', 'addMaterial');

        $validated = $request->validateWithBag('addMaterial', [
            'material_name' => 'required|max:255|unique:material_categories,material_name',
        ]);

        MaterialCategory::create($validated);

        return redirect()->route('owner.manage-products')->with('success_add', 'Material added successfully.');
    }

    public function update(Request $request, MaterialCategory $materialCategory)
    {
        // supaya modal edit tetap kebuka kalau error
        session()->flash('openModal', 'editMaterial');
        session()->flash('editMaterialId', $materialCategory->id);

        $validated = $request->validateWithBag('editMaterial', [
            'material_name' => 'required|max:255|unique:material_categories,material_name,' . $materialCategory->id,
        ]);

        $materialCategory->update(array_filter($validated));

        return redirect()->route('owner.manage-products')->with('success_edit', 'Product updated successfully.');
    }

    public function destroy(MaterialCategory $materialCategory)
    {
        $materialCategory->delete();

        return redirect()->route('owner.manage-products')->with('success', 'Product Category deleted successfully.');
    }
}
