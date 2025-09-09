@extends('layouts.app')
@section('title', 'Manage Products')
@section('content')

    <x-nav-locate :items="['Menu', 'Manage Data', 'Products']" />

    <div x-data="{
        openModal: '{{ session('openModal') }}',
        editCategory: {},
        editMaterial: {},
        editTexture: {},
        editSleeve: {},
        editSize: {},
        editShipping: {},
        searchCategory: '',
        searchMaterial: '',
        searchTexture: '',
        searchSleeve: '',
        searchSize: '',
        searchShipping: ''
    }" class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- ===================== PRODUCT CATEGORY ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <h2 class="text-xl font-semibold text-gray-900">Product Category</h2>
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto">
                    <div class="w-full md:w-48">
                        <div class="relative">
                            <x-icons.search />
                            <input type="text" x-model="searchCategory" placeholder="Search Category"
                                class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                        </div>
                    </div>
                    <button @click="openModal = 'addCategory'"
                        class="w-32 px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm">+ Add</button>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Category</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($productCategories as $cat)
                                <tr class="border-t border-gray-200"
                                    x-show="'{{ strtolower($cat->category_name) }}'.includes(searchCategory.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $cat->category_name }}</td>
                                    <td class="py-2 px-4 text-right">
                                        <button @click="editCategory = {{ $cat->toJson() }}; openModal = 'editCategory'"
                                            class="px-3 py-1 border rounded-md hover:bg-gray-50">Edit</button>
                                        <form action="{{ route('owner.product-categories.destroy', $cat) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600"
                                                onclick="return confirm('Delete this category?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== MATERIAL CATEGORY ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <h2 class="text-xl font-semibold text-gray-900">Material Category</h2>
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto">
                    <div class="w-full md:w-72">
                        <div class="relative">
                            <x-icons.search />
                            <input type="text" x-model="searchMaterial" placeholder="Search Material"
                                class="w-full rounded-md border pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-green-200" />
                        </div>
                    </div>
                    <button @click="openModal = 'addMaterial'"
                        class="w-32 px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm">+ Add</button>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="py-2 px-4">No</th>
                            <th class="py-2 px-4">Material</th>
                            <th class="py-2 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materialCategories as $mat)
                            <tr class="border-t border-gray-200"
                                x-show="'{{ strtolower($mat->category_name) }}'.includes(searchMaterial.toLowerCase())">
                                <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4">{{ $mat->category_name }}</td>
                                <td class="py-2 px-4 text-right">
                                    <button @click="editMaterial = {{ $mat->toJson() }}; openModal = 'editMaterial'"
                                        class="px-3 py-1 border rounded-md hover:bg-gray-50">Edit</button>
                                    <form action="{{ route('owner.material-categories.destroy', $mat) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600"
                                            onclick="return confirm('Delete this material?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ===================== MATERIAL TEXTURE ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <h2 class="text-xl font-semibold text-gray-900">Material Texture</h2>
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto">
                    <div class="w-full md:w-72">
                        <div class="relative">
                            <x-icons.search />
                            <input type="text" x-model="searchTexture" placeholder="Search Texture"
                                class="w-full rounded-md border pl-9 pr-3 py-2 text-sm focus:ring-green-200" />
                        </div>
                    </div>
                    <button @click="openModal = 'addTexture'"
                        class="w-32 px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm">+ Add</button>
                </div>
            </div>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="py-2 px-4">No</th>
                        <th class="py-2 px-4">Texture</th>
                        <th class="py-2 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materialTextures as $tex)
                        <tr class="border-t border-gray-200"
                            x-show="'{{ strtolower($tex->texture_name) }}'.includes(searchTexture.toLowerCase())">
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">{{ $tex->texture_name }}</td>
                            <td class="py-2 px-4 text-right">
                                <button @click="editTexture = {{ $tex->toJson() }}; openModal = 'editTexture'"
                                    class="px-3 py-1 border rounded-md hover:bg-gray-50">Edit</button>
                                <form action="{{ route('owner.material-textures.destroy', $tex) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600"
                                        onclick="return confirm('Delete this texture?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- ===================== MATERIAL SLEEVE ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">Material Sleeve</h2>
            <div class="relative mb-3">
                <x-icons.search class="absolute left-2 top-1/2 -translate-y-1/2" />
                <input type="text" x-model="searchSleeve" placeholder="Search Sleeve"
                    class="w-full rounded-md border pl-9 pr-3 py-2 text-sm focus:ring-green-200" />
            </div>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="py-2 px-4">No</th>
                        <th class="py-2 px-4">Sleeve</th>
                        <th class="py-2 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materialSleeves as $slv)
                        <tr class="border-t border-gray-200"
                            x-show="'{{ strtolower($slv->sleeve_name) }}'.includes(searchSleeve.toLowerCase())">
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">{{ $slv->sleeve_name }}</td>
                            <td class="py-2 px-4 text-right">
                                <button @click="editSleeve = {{ $slv->toJson() }}; openModal = 'editSleeve'"
                                    class="px-3 py-1 border rounded-md hover:bg-gray-50">Edit</button>
                                <form action="{{ route('owner.material-sleeves.destroy', $slv) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600"
                                        onclick="return confirm('Delete this sleeve?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- ===================== MATERIAL SIZE ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">Material Size</h2>
            <div class="relative mb-3">
                <x-icons.search class="absolute left-2 top-1/2 -translate-y-1/2" />
                <input type="text" x-model="searchSize" placeholder="Search Size"
                    class="w-full rounded-md border pl-9 pr-3 py-2 text-sm focus:ring-green-200" />
            </div>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="py-2 px-4">No</th>
                        <th class="py-2 px-4">Size</th>
                        <th class="py-2 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materialSizes as $sz)
                        <tr class="border-t border-gray-200"
                            x-show="'{{ strtolower($sz->size_name) }}'.includes(searchSize.toLowerCase())">
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">{{ $sz->size_name }}</td>
                            <td class="py-2 px-4 text-right">
                                <button @click="editSize = {{ $sz->toJson() }}; openModal = 'editSize'"
                                    class="px-3 py-1 border rounded-md hover:bg-gray-50">Edit</button>
                                <form action="{{ route('owner.material-sizes.destroy', $sz) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600"
                                        onclick="return confirm('Delete this size?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- ===================== SHIPPING ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">Shippings</h2>
            <div class="relative mb-3">
                <x-icons.search class="absolute left-2 top-1/2 -translate-y-1/2" />
                <input type="text" x-model="searchShipping" placeholder="Search Shipping"
                    class="w-full rounded-md border pl-9 pr-3 py-2 text-sm focus:ring-green-200" />
            </div>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="py-2 px-4">No</th>
                        <th class="py-2 px-4">Shipping</th>
                        <th class="py-2 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shippings as $ship)
                        <tr class="border-t border-gray-200"
                            x-show="'{{ strtolower($ship->shipping_name) }}'.includes(searchShipping.toLowerCase())">
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">{{ $ship->shipping_name }}</td>
                            <td class="py-2 px-4 text-right">
                                <button @click="editShipping = {{ $ship->toJson() }}; openModal = 'editShipping'"
                                    class="px-3 py-1 border rounded-md hover:bg-gray-50">Edit</button>
                                <form action="{{ route('owner.shippings.destroy', $ship) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600"
                                        onclick="return confirm('Delete this shipping?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

    </div>

    {{-- ===================== MODALS ===================== --}}

    {{-- Add Category Modal --}}
    <div x-show="openModal === 'addCategory'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Add Product Category</h3>
            <form action="{{ route('owner.product-categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Category Name</label>
                    <input type="text" name="category_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Category Modal --}}
    <div x-show="openModal === 'editCategory'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Edit Product Category</h3>
            <form x-bind:action="'/owner/product-categories/' + editCategory.id" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Category Name</label>
                    <input type="text" name="category_name" x-model="editCategory.category_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Material Modal --}}
    <div x-show="openModal === 'addMaterial'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Add Material Category</h3>
            <form action="{{ route('owner.material-categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Material Name</label>
                    <input type="text" name="category_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Material Modal --}}
    <div x-show="openModal === 'editMaterial'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Edit Material Category</h3>
            <form x-bind:action="'/owner/material-categories/' + editMaterial.id" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Material Name</label>
                    <input type="text" name="category_name" x-model="editMaterial.category_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Texture Modal --}}
    <div x-show="openModal === 'addTexture'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Add Material Texture</h3>
            <form action="{{ route('owner.material-textures.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Texture Name</label>
                    <input type="text" name="texture_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Texture Modal --}}
    <div x-show="openModal === 'editTexture'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Edit Material Texture</h3>
            <form x-bind:action="'/owner/material-textures/' + editTexture.id" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Texture Name</label>
                    <input type="text" name="texture_name" x-model="editTexture.texture_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Sleeve Modal --}}
    <div x-show="openModal === 'addSleeve'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Add Material Sleeve</h3>
            <form action="{{ route('owner.material-sleeves.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Sleeve Name</label>
                    <input type="text" name="sleeve_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Sleeve Modal --}}
    <div x-show="openModal === 'editSleeve'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Edit Material Sleeve</h3>
            <form x-bind:action="'/owner/material-sleeves/' + editSleeve.id" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Sleeve Name</label>
                    <input type="text" name="sleeve_name" x-model="editSleeve.sleeve_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Size Modal --}}
    <div x-show="openModal === 'addSize'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Add Material Size</h3>
            <form action="{{ route('owner.material-sizes.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Size Name</label>
                    <input type="text" name="size_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Size Modal --}}
    <div x-show="openModal === 'editSize'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Edit Material Size</h3>
            <form x-bind:action="'/owner/material-sizes/' + editSize.id" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Size Name</label>
                    <input type="text" name="size_name" x-model="editSize.size_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Shipping Modal --}}
    <div x-show="openModal === 'addShipping'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Add Shipping</h3>
            <form action="{{ route('owner.shippings.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Shipping Name</label>
                    <input type="text" name="shipping_name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Shipping Modal --}}
    <div x-show="openModal === 'editShipping'" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Edit Shipping</h3>
        </div>
    </div>

    <script>
        // Update form actions for edit modals
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                if (Alpine.store('editCategory')) {
                    const form = document.querySelector('[x-ref="editCategoryForm"]');
                    if (form) form.action = '{{ url('owner/product-categories') }}/' + Alpine.store(
                        'editCategory').id;
                }
                if (Alpine.store('editMaterial')) {
                    const form = document.querySelector('[x-ref="editMaterialForm"]');
                    if (form) form.action = '{{ url('owner/material-categories') }}/' + Alpine.store(
                        'editMaterial').id;
                }
                if (Alpine.store('editTexture')) {
                    const form = document.querySelector('[x-ref="editTextureForm"]');
                    if (form) form.action = '{{ url('owner/material-textures') }}/' + Alpine.store(
                        'editTexture').id;
                }
                if (Alpine.store('editSleeve')) {
                    const form = document.querySelector('[x-ref="editSleeveForm"]');
                    if (form) form.action = '{{ url('owner/material-sleeves') }}/' + Alpine.store(
                        'editSleeve').id;
                }
                if (Alpine.store('editSize')) {
                    const form = document.querySelector('[x-ref="editSizeForm"]');
                    if (form) form.action = '{{ url('owner/material-sizes') }}/' + Alpine.store('editSize')
                        .id;
                }
                if (Alpine.store('editShipping')) {
                    const form = document.querySelector('[x-ref="editShippingForm"]');
                    if (form) form.action = '{{ url('owner/shippings') }}/' + Alpine.store('editShipping')
                        .id;
                }
            });
        });
    </script>
@endsection
