@extends('layouts.app')
@section('title', 'Manage Products')
@section('content')

    <x-nav-locate :items="['Menu', 'Manage Data', 'Products']" />

    {{-- Root Alpine State --}}
    <div x-data="{
        openModal: '{{ session('openModal') }}',
        searchProduct: '',
        searchMaterial: '',
        searchTexture: '',
        searchSleeve: '',
        searchSize: '',
        searchService: '',
        searchShipping: '',
        editProduct: {},
        editMaterial: {},
        editTexture: {},
        editSleeve: {},
        editSize: {},
        editService: {},
        editShipping: {},
    }" class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- ===================== Product Category ===================== --}}
        <section id="product-categories" class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                {{-- Judul --}}
                <h2 class="text-xl font-semibold text-gray-900 flex-shrink-0">
                    Product Category
                </h2>

                {{-- Spacer biar search bisa fleksibel --}}
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-[100px]">
                        <x-icons.search />
                        <input type="text" x-model="searchProduct" placeholder="Search Items"
                            class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                    </div>

                    {{-- Add Items --}}
                    <button @click="openModal = 'addProduct'"
                        class="cursor-pointer flex-shrink-0 w-32 whitespace-nowrap px-3 py-2 rounded-md
                   bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Items
                    </button>
                </div>
            </div>

            {{-- Table Product Category --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-[300px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Product Name</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productCategories as $product)
                                <tr class="border-t border-gray-200"
                                    x-show="searchProduct.length < 1 || '{{ strtolower($product->product_name) }}'.includes(searchProduct.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $product->product_name }}</td>
                                    <td class="py-2 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Edit --}}
                                            <button
                                                @click="editProduct = {{ $product->toJson() }}; openModal = 'editProduct'"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Edit">
                                                <x-icons.edit class="w-3 h-3 !mr-0" />
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form
                                                action="{{ route('owner.manage-data.products.product-categories.destroy', $product) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this product?')"
                                                    title="Delete">
                                                    <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-center text-red-500 border-t border-gray-200">
                                        No Product Categories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== Material Category ===================== --}}
        <section id="material-categories" class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                {{-- Judul --}}
                <h2 class="text-xl font-semibold text-gray-900 flex-shrink-0">
                    Material Category
                </h2>

                {{-- Spacer biar search bisa fleksibel --}}
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-[100px]">
                        <x-icons.search />
                        <input type="text" x-model="searchMaterial" placeholder="Search Items"
                            class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                    </div>

                    {{-- Add Items --}}
                    <button @click="openModal = 'addMaterial'"
                        class="cursor-pointer flex-shrink-0 w-32 whitespace-nowrap px-3 py-2 rounded-md
                   bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Items
                    </button>
                </div>
            </div>

            {{-- Table Material Category --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-[300px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Material Name</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materialCategories as $material)
                                <tr class="border-t border-gray-200"
                                    x-show="searchMaterial.length < 1 || '{{ strtolower($material->material_name) }}'.includes(searchMaterial.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $material->material_name }}</td>
                                    <td class="py-2 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Edit --}}
                                            <button
                                                @click="editMaterial = {{ $material->toJson() }}; openModal = 'editMaterial'"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Edit">
                                                <x-icons.edit class="w-3 h-3 !mr-0" />
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form
                                                action="{{ route('owner.manage-data.products.material-categories.destroy', $material) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this material?')"
                                                    title="Delete">
                                                    <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-center text-red-500 border-t border-gray-200">
                                        No Material Categories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== Material Texture ===================== --}}
        <section id="material-textures" class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                {{-- Judul --}}
                <h2 class="text-xl font-semibold text-gray-900 flex-shrink-0">
                    Material Texture
                </h2>

                {{-- Spacer biar search bisa fleksibel --}}
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-[100px]">
                        <x-icons.search />
                        <input type="text" x-model="searchTexture" placeholder="Search Items"
                            class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                    </div>

                    {{-- Add Items --}}
                    <button @click="openModal = 'addTexture'"
                        class="cursor-pointer flex-shrink-0 w-32 whitespace-nowrap px-3 py-2 rounded-md
                   bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Items
                    </button>
                </div>
            </div>

            {{-- Table Material Texture --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-[300px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Texture Name</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materialTextures as $texture)
                                <tr class="border-t border-gray-200"
                                    x-show="searchTexture.length < 1 || '{{ strtolower($texture->texture_name) }}'.includes(searchTexture.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $texture->texture_name }}</td>
                                    <td class="py-2 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Edit --}}
                                            <button
                                                @click="editTexture = {{ $texture->toJson() }}; openModal = 'editTexture'"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Edit">
                                                <x-icons.edit class="w-3 h-3 !mr-0" />
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form
                                                action="{{ route('owner.manage-data.products.material-textures.destroy', $texture) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this texture?')"
                                                    title="Delete">
                                                    <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-3 px-4 text-center text-red-500 border-t border-gray-200">
                                        No Material Textures found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== Material Sleeve ===================== --}}
        <section id="material-sleeves" class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <h2 class="text-xl font-semibold text-gray-900 flex-shrink-0">
                    Material Sleeve
                </h2>

                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-[100px]">
                        <x-icons.search />
                        <input type="text" x-model="searchSleeve" placeholder="Search Items"
                            class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                    </div>

                    {{-- Add Items --}}
                    <button @click="openModal = 'addSleeve'"
                        class="cursor-pointer flex-shrink-0 w-32 whitespace-nowrap px-3 py-2 rounded-md
                bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Items
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-[300px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Sleeve Name</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materialSleeves as $sleeve)
                                <tr class="border-t border-gray-200"
                                    x-show="searchSleeve.length < 1 || '{{ strtolower($sleeve->sleeve_name) }}'.includes(searchSleeve.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $sleeve->sleeve_name }}</td>
                                    <td class="py-2 px-4 text-right">
                                        {{-- Tombol Edit --}}
                                        <button @click="editSleeve = {{ $sleeve->toJson() }}; openModal = 'editSleeve'"
                                            class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                            title="Edit">
                                            <x-icons.edit class="w-3 h-3 !mr-0" />
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <form
                                            action="{{ route('owner.manage-data.products.material-sleeves.destroy', $sleeve->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                onclick="return confirm('Are you sure you want to delete this sleeve?')"
                                                title="Delete">
                                                <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-3 px-4 text-center text-red-500 border-t border-gray-200">
                                        No Material Sleeves found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== Material Size ===================== --}}
        <section id="material-sizes" class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                {{-- Judul --}}
                <h2 class="text-xl font-semibold text-gray-900 flex-shrink-0">
                    Material Size
                </h2>

                {{-- Spacer biar search bisa fleksibel --}}
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-[100px]">
                        <x-icons.search />
                        <input type="text" x-model="searchSize" placeholder="Search Items"
                            class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                    </div>

                    {{-- Add Items --}}
                    <button @click="openModal = 'addSize'"
                        class="cursor-pointer flex-shrink-0 w-32 whitespace-nowrap px-3 py-2 rounded-md
                   bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Items
                    </button>
                </div>
            </div>


            {{-- Table Material Size --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-[300px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Size Name</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materialSizes as $size)
                                <tr class="border-t border-gray-200"
                                    x-show="searchSize.length < 1 || '{{ strtolower($size->size_name) }}'.includes(searchSize.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $size->size_name }}</td>
                                    <td class="py-2 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Edit --}}
                                            <button @click="editSize = {{ $size->toJson() }}; openModal = 'editSize'"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Edit">
                                                <x-icons.edit class="w-3 h-3 !mr-0" />
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form
                                                action="{{ route('owner.manage-data.products.material-sizes.destroy', $size) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this size?')"
                                                    title="Delete">
                                                    <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-3 px-4 text-center text-red-500 border-t border-gray-200">
                                        No Material Size found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== Service ===================== --}}
        <section id="services" class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                {{-- Judul --}}
                <h2 class="text-xl font-semibold text-gray-900 flex-shrink-0">
                    Service
                </h2>

                {{-- Spacer biar search bisa fleksibel --}}
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-[100px]">
                        <x-icons.search />
                        <input type="text" x-model="searchService" placeholder="Search Items"
                            class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                    </div>

                    {{-- Add Items --}}
                    <button @click="openModal = 'addService'"
                        class="cursor-pointer flex-shrink-0 w-32 whitespace-nowrap px-3 py-2 rounded-md
                   bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Items
                    </button>
                </div>
            </div>


            {{-- Table Services --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-[300px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Service Name</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($services as $service)
                                <tr class="border-t border-gray-200"
                                    x-show="searchService.length < 1 || '{{ strtolower($service->service_name) }}'.includes(searchService.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $service->service_name }}</td>
                                    <td class="py-2 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Edit --}}
                                            <button
                                                @click="editService = {{ $service->toJson() }}; openModal = 'editService'"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Edit">
                                                <x-icons.edit class="w-3 h-3 !mr-0" />
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form
                                                action="{{ route('owner.manage-data.products.services.destroy', $service) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this service?')"
                                                    title="Delete">
                                                    <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-3 px-4 text-center text-red-500 border-t border-gray-200">
                                        No Services found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== Shipping ===================== --}}
        <section id="shippings" class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                {{-- Judul --}}
                <h2 class="text-xl font-semibold text-gray-900 flex-shrink-0">
                    Shipping
                </h2>

                {{-- Spacer biar search bisa fleksibel --}}
                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-[100px]">
                        <x-icons.search />
                        <input type="text" x-model="searchShipping" placeholder="Search Items"
                            class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                    </div>

                    {{-- Add Items --}}
                    <button @click="openModal = 'addShipping'"
                        class="cursor-pointer flex-shrink-0 w-32 whitespace-nowrap px-3 py-2 rounded-md
                   bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Items
                    </button>
                </div>
            </div>


            {{-- Table Product Category --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-72 overflow-y-auto">
                    <table class="min-w-[300px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Shipping Name</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materialShippings as $shipping)
                                <tr class="border-t border-gray-200"
                                    x-show="searchShipping.length < 1 || '{{ strtolower($shipping->shipping_name) }}'.includes(searchShipping.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $shipping->shipping_name }}</td>
                                    <td class="py-2 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Edit --}}
                                            <button
                                                @click="editShipping = {{ $shipping->toJson() }}; openModal = 'editShipping'"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Edit">
                                                <x-icons.edit class="w-3 h-3 !mr-0" />
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form
                                                action="{{ route('owner.manage-data.products.shippings.destroy', $shipping) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this shipping?')"
                                                    title="Delete">
                                                    <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-3 px-4 text-center text-red-500 border-t border-gray-200">
                                        No Shippings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== MODALS ===================== --}}
        {{-- ========== Add & Edit Product Category Modal ========== --}}
        <div x-show="openModal === 'addProduct'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add Product Category</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form action="{{ route('owner.manage-data.products.product-categories.store') . '#product-categories' }}"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Name</label>
                        <div class="relative">
                            <input type="text" name="product_name" value="{{ old('product_name') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addProduct->has('product_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addProduct->has('product_name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('product_name', 'addProduct')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="openModal === 'editProduct'" x-cloak x-init="@if (session('openModal') === 'editProduct' && session('editProductId')) editProduct = {{ \App\Models\ProductCategory::find(session('editProductId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Product Category</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form :action="`/owner/manage-data/products/product-categories/${editProduct.id}'#product-categories'`"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="product_name" x-model="editProduct.product_name"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editProduct->has('product_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('product_name', 'editProduct')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Add & Edit Material Category Modal ========== --}}
        <div x-show="openModal === 'addMaterial'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add Material Category</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form
                    action="{{ route('owner.manage-data.products.material-categories.store') . '#material-categories' }}"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Material Name</label>
                        <div class="relative">
                            <input type="text" name="material_name" value="{{ old('material_name') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addMaterial->has('material_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addMaterial->has('material_name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('material_name', 'addMaterial')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="openModal === 'editMaterial'" x-cloak x-init="@if (session('openModal') === 'editMaterial' && session('editMaterialId')) editMaterial = {{ \App\Models\MaterialCategory::find(session('editMaterialId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Material Category</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form :action="`/owner/manage-data/products/material-categories/${editMaterial.id}'#material-categories'`"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Material Name</label>
                        <input type="text" name="material_name" x-model="editMaterial.material_name"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editMaterial->has('material_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('material_name', 'editMaterial')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Add & Edit Material Texture Modal ========== --}}
        <div x-show="openModal === 'addTexture'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add Material Texture</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form action="{{ route('owner.manage-data.products.material-textures.store') . '#material-textures' }}"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Texture Name</label>
                        <div class="relative">
                            <input type="text" name="texture_name" value="{{ old('texture_name') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addTexture->has('texture_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addTexture->has('texture_name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('texture_name', 'addTexture')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="openModal === 'editTexture'" x-cloak x-init="@if (session('openModal') === 'editTexture' && session('editTextureId')) editTexture = {{ \App\Models\MaterialTexture::find(session('editTextureId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Material Texture</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form :action="`/owner/manage-data/products/material-textures/${editTexture.id}'#material-textures'`"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Texture Name</label>
                        <input type="text" name="texture_name" x-model="editTexture.texture_name"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editTexture->has('texture_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('texture_name', 'editTexture')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Add & Edit Material Sleeve Modal ========== --}}
        <div x-show="openModal === 'addSleeve'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add Material Sleeve</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form action="{{ route('owner.manage-data.products.material-sleeves.store') . '#material-sleeves' }}"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sleeve Name</label>
                        <div class="relative">
                            <input type="text" name="sleeve_name" value="{{ old('sleeve_name') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addSleeve->has('sleeve_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addSleeve->has('sleeve_name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('sleeve_name', 'addSleeve')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="openModal === 'editSleeve'" x-cloak x-init="@if (session('openModal') === 'editSleeve' && session('editSleeveId')) editSleeve = {{ \App\Models\MaterialSleeve::find(session('editSleeveId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Material Sleeve</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form :action="`/owner/manage-data/products/material-sleeves/${editSleeve.id}'#material-sleeves'`"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sleeve Name</label>
                        <input type="text" name="sleeve_name" x-model="editSleeve.sleeve_name"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editSleeve->has('sleeve_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('sleeve_name', 'editSleeve')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Add & Edit Material Size Modal ========== --}}
        <div x-show="openModal === 'addSize'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add Material Size</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form action="{{ route('owner.manage-data.products.material-sizes.store') . '#material-sizes' }}"
                    method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Size Name</label>
                        <div class="relative">
                            <input type="text" name="size_name" value="{{ old('size_name') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addSize->has('size_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addSize->has('size_name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('size_name', 'addSize')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="openModal === 'editSize'" x-cloak x-init="@if (session('openModal') === 'editSize' && session('editSizeId')) editSize = {{ \App\Models\MaterialSize::find(session('editSizeId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Material Size</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form :action="`/owner/manage-data/products/material-sizes/${editSize.id}'#material-sizes'`" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Size Name</label>
                        <input type="text" name="size_name" x-model="editSize.size_name"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editSize->has('size_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('size_name', 'editSize')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Add & Edit Service Modal ========== --}}
        <div x-show="openModal === 'addService'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add Service</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form action="{{ route('owner.manage-data.products.services.store') . '#services' }}" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service Name</label>
                        <div class="relative">
                            <input type="text" name="service_name" value="{{ old('service_name') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addService->has('service_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addService->has('service_name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('service_name', 'addService')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="openModal === 'editService'" x-cloak x-init="@if (session('openModal') === 'editService' && session('editServiceId')) editService = {{ \App\Models\Service::find(session('editServiceId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Service</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form :action="`/owner/manage-data/products/services/${editService.id}'#services'`" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input type="text" name="service_name" x-model="editService.service_name"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editService->has('service_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('service_name', 'editService')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Add & Edit Shippings Modal ========== --}}
        <div x-show="openModal === 'addShipping'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add Shippings</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form action="{{ route('owner.manage-data.products.shippings.store') . '#shippings' }}" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Shipping Name</label>
                        <div class="relative">
                            <input type="text" name="shipping_name" value="{{ old('shipping_name') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addShipping->has('shipping_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addShipping->has('shipping_name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('shipping_name', 'addShipping')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="openModal === 'editShipping'" x-cloak x-init="@if (session('openModal') === 'editShipping' && session('editShippingId')) editShipping = {{ \App\Models\Shipping::find(session('editShippingId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Shipping</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form :action="`/owner/manage-data/products/shippings/${editShipping.id}#shippings`" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Shipping Name</label>
                        <input type="text" name="shipping_name" x-model="editShipping.shipping_name"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editShipping->has('shipping_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('shipping_name', 'editShipping')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
