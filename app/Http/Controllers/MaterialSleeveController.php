<?php

namespace App\Http\Controllers;

use App\Models\MaterialSleeve;
use Illuminate\Http\Request;

class MaterialSleeveController extends Controller
{
    public function store(Request $request)
    {
        session()->flash('openModal', 'addSleeve');

        $validated = $request->validateWithBag('addSleeve', [
            'sleeve_name' => 'required|string|max:100|unique:material_sleeves,sleeve_name',
        ]);

        MaterialSleeve::create($validated);

        return back()->with('success', 'Material Sleeve added successfully.');
    }

    public function update(Request $request, MaterialSleeve $materialSleeve)
    {
        session()->flash('openModal', 'editSleeve');
        session()->flash('editSleeveId', $materialSleeve->id);

        $validated = $request->validateWithBag('editSleeve', [
            'sleeve_name' => 'required|string|max:100|unique:material_sleeves,sleeve_name,' . $materialSleeve->id,
        ]);

        $materialSleeve->update($validated);

        return back()->with('success', 'Material Sleeve updated successfully.');
    }

    public function destroy(MaterialSleeve $materialSleeve)
    {
        $materialSleeve->delete();

        return back()->with('success', 'Material Sleeve deleted successfully.');
    }
}
