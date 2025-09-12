<?php

namespace App\Http\Controllers;

use App\Models\MaterialTexture;
use Illuminate\Http\Request;

class MaterialTextureController extends Controller
{

    public function store(Request $request)
    {
        // simpan state modal
        session()->flash('openModal', 'addTexture');

        $validated = $request->validateWithBag('addTexture', [
            'texture_name' => 'required|max:255|unique:material_textures,texture_name',
        ]);

        MaterialTexture::create($validated);

        return redirect()->to(route('owner.manage-data.products.index') . '#material-textures')
            ->with('success_add', 'Texture added successfully.');
    }

    public function update(Request $request, MaterialTexture $material_texture)
    {
        // supaya modal edit tetap kebuka kalau error
        session()->flash('openModal', 'editTexture');
        session()->flash('editTextureId',  $material_texture->id);

        $validated = $request->validateWithBag('editTexture', [
            'texture_name' => 'required|max:255|unique:material_textures,texture_name,' . $material_texture->id,
        ]);

        $material_texture->update(array_filter($validated));

        return redirect()->to(route('owner.manage-data.products.index') . '#material-textures')
            ->with('success_edit', 'Product updated successfully.');
    }

    public function destroy(MaterialTexture $material_texture)
    {
        $material_texture->delete();

        return redirect()->to(route('owner.manage-data.products.index') . '#material-textures')
            ->with('success', 'Product Category deleted successfully.');
    }
}
