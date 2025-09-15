@extends('layouts.app')

@section('title', 'Create Orders')

@section('content')
    @php
        $role = auth()->user()?->role;
        $root = $role === 'owner' ? 'Admin' : 'Menu';
    @endphp

    <x-nav-locate :items="[$root, 'Orders', 'Create Order']" />

    <form x-data="orderForm()" @submit.prevent="submitForm" class="bg-white rounded-lg shadow p-6 space-y-8">

        @csrf

        {{-- ================= Header ================= --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-8">
            <h2 class="text-xl font-semibold text-gray-900">Create Order</h2>
            <div class="flex md:flex-row gap-x-4 md:gap-x-6 gap-y-4 w-full md:w-auto">
                {{-- Order Date --}}
                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3 w-full md:w-auto">
                    <label for="order_date" class="text-sm text-gray-600 md:w-20">Order Date</label>
                    <div class="relative w-full md:w-55">
                        <input id="order_date" name="order_date" type="date" x-model="order_date"
                            value="{{ old('order_date') }}"
                            class="mt-1 w-full rounded-md px-3 py-2 text-sm border {{ $errors->addOrder->has('order_date') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700" />

                        @error('order_date', 'addOrder')
                            <p class="absolute left-0 -bottom-5 text-[10px] md:text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <span x-show="errors.order_date" x-text="errors.order_date"
                            class="absolute left-0 -bottom-5 text-[10px] md:text-xs text-red-600"></span>
                    </div>
                </div>

                {{-- Deadline --}}
                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3 w-full md:w-auto">
                    <label for="deadline" class="text-sm text-gray-600 md:w-16">Deadline</label>
                    <div class="relative w-full md:w-55">
                        <input id="deadline" name="deadline" type="date" x-model="deadline"
                            value="{{ old('deadline') }}"
                            class="mt-1 w-full rounded-md px-3 py-2 text-sm border {{ $errors->addOrder->has('deadline') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700" />
                        @error('deadline', 'addOrder')
                            <p class="absolute left-0 -bottom-5 text-[10px] md:text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <span x-show="errors.deadline" x-text="errors.deadline"
                            class="absolute left-0 -bottom-5 text-[10px] md:text-xs text-red-600"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= Customers & Sales ================= --}}
        <section class="space-y-5 border-b pb-12">
            <h3 class="text-lg font-semibold text-gray-800">Data Customers & Sales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                {{-- Customer --}}
                <div class="relative w-full min-h-[60px]">
                    <label class="block text-sm text-gray-600 mb-1">Customer</label>
                    <div class="relative">
                        <select name="customer_id" x-model="customer_id"
                            class="appearance-none w-full rounded-md border px-3 py-2 text-sm text-gray-700
                    {{ $errors->addOrder->has('customer_id')
                        ? 'border-red-500 focus:border-red-500 focus:ring-red-200'
                        : 'border-gray-300 focus:border-green-500 focus:ring-green-200' }}
                    focus:outline-none focus:ring-2">
                            <option value="">-- Select Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Custom Arrow --}}
                        <div class="pointer-events-none absolute top-1/2 -translate-y-1/2 right-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        {{-- Danger Icon --}}
                        @if ($errors->addOrder->has('customer_id'))
                            <span class="absolute top-1/2 -translate-y-1/2 right-8 text-red-500 pointer-events-none">
                                <x-icons.danger />
                            </span>
                        @endif

                        {{-- Error Message --}}
                        @error('customer_id', 'addOrder')
                            <p class="absolute left-0 -bottom-5 text-[10px] text-red-600">{{ $message }}</p>
                        @enderror
                        <span x-show="errors.customer_id" x-text="errors.customer_id"
                            class="absolute left-0 -bottom-5 text-[10px] text-red-600"></span>
                    </div>
                </div>

                {{-- Sales --}}
                <div class="relative w-full min-h-[60px]">
                    <label class="block text-sm text-gray-600 mb-1">Sales</label>
                    <div class="relative">
                        <select name="sales_id" x-model="sales_id"
                            class="appearance-none w-full rounded-md border px-3 py-2 text-sm text-gray-700
                    {{ $errors->addOrder->has('sales_id')
                        ? 'border-red-500 focus:border-red-500 focus:ring-red-200'
                        : 'border-gray-300 focus:border-green-500 focus:ring-green-200' }}
                    focus:outline-none focus:ring-2">
                            <option value="">-- Select Sales --</option>
                            @foreach ($sales as $sale)
                                <option value="{{ $sale->id }}" {{ old('sales_id') == $sale->id ? 'selected' : '' }}>
                                    {{ $sale->sales_name }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Custom Arrow --}}
                        <div class="pointer-events-none absolute top-1/2 -translate-y-1/2 right-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        {{-- Danger Icon --}}
                        @if ($errors->addOrder->has('sales_id'))
                            <span class="absolute top-1/2 -translate-y-1/2 right-8 text-red-500 pointer-events-none">
                                <x-icons.danger />
                            </span>
                        @endif

                        {{-- Error Message --}}
                        @error('sales_id', 'addOrder')
                            <p class="absolute left-0 -bottom-5 text-[10px] text-red-600">{{ $message }}</p>
                        @enderror
                        <span x-show="errors.sales_id" x-text="errors.sales_id"
                            class="absolute left-0 -bottom-5 text-[10px] text-red-600"></span>
                    </div>
                </div>
            </div>
        </section>


        {{-- ================= Detail Products ================= --}}
        <section class="space-y-5 border-b pb-12">
            <h3 class="text-lg font-semibold text-gray-800">Detail Products</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <div class="space-y-7">
                    {{-- Product --}}
                    <div class="relative flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Product</label>
                        <div class="relative w-full">
                            <select name="product_category_id" x-model="product_category_id"
                                class="appearance-none w-full rounded-md border px-3 py-2 text-sm text-gray-700
                        {{ $errors->addOrder->has('product_category_id')
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-200'
                            : 'border-gray-300 focus:border-green-500 focus:ring-green-200' }} 
                        focus:outline-none focus:ring-2">
                                <option value="">-- Select Product --</option>
                                @foreach ($productCategories as $product)
                                    <option value="{{ $product->id }}"
                                        {{ old('product_category_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->product_name }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Custom Arrow --}}
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @if ($errors->addOrder->has('product_category_id'))
                                <span class="absolute right-8 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">
                                    <x-icons.danger />
                                </span>
                            @endif
                            @error('product_category_id', 'addOrder')
                                <p class="absolute left-0 -bottom-5 text-[10px] text-red-600">{{ $message }}</p>
                            @enderror
                            <span x-show="errors.product_category_id" x-text="errors.product_category_id"
                                class="absolute left-0 -bottom-5 text-[10px] text-red-600"></span>
                        </div>
                    </div>

                    {{-- Color --}}
                    <div class="relative flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Color</label>
                        <div class="relative w-full">
                            <input type="text" name="product_color" x-model="product_color"
                                value="{{ old('product_color') }}"
                                class="w-full rounded-md border px-3 py-2 text-sm text-gray-700
                        {{ $errors->addOrder->has('product_color')
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-200'
                            : 'border-gray-300 focus:border-green-500 focus:ring-green-200' }} 
                        focus:outline-none focus:ring-2"
                                placeholder="Enter color" />
                            @if ($errors->addOrder->has('product_color'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">
                                    <x-icons.danger />
                                </span>
                            @endif
                            @error('product_color', 'addOrder')
                                <p class="absolute left-0 -bottom-5 text-[10px] text-red-600">{{ $message }}</p>
                            @enderror
                            <span x-show="errors.product_color" x-text="errors.product_color"
                                class="absolute left-0 -bottom-5 text-[10px] text-red-600"></span>
                        </div>
                    </div>

                    {{-- Materials --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Materials</label>
                        <div class="flex flex-col md:flex-row gap-2 gap-y-6 md:gap-3 w-full">

                            {{-- Material --}}
                            <div class="relative w-full">
                                <select name="material_category_id" x-model="material_category_id"
                                    class="appearance-none w-full rounded-md border px-3 py-2 text-sm text-gray-700 {{ $errors->addOrder->has('material_category_id') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2">
                                    <option value="">-- Select Material --</option>
                                    @foreach ($materialCategories as $material)
                                        <option value="{{ $material->id }}"
                                            {{ old('material_category_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->material_name }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Custom Arrow --}}
                                <div
                                    class="pointer-events-none absolute top-1/2 -translate-y-1/2 right-3 flex items-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                {{-- Danger Icon --}}
                                @if ($errors->addOrder->has('material_category_id'))
                                    <span
                                        class="absolute top-1/2 -translate-y-1/2 right-8 text-red-500 pointer-events-none">
                                        <x-icons.danger />
                                    </span>
                                @endif

                                {{-- Error Message --}}
                                @error('material_category_id', 'addOrder')
                                    <p class="absolute left-0 -bottom-5 text-[10px] text-red-600">{{ $message }}</p>
                                @enderror
                                <span x-show="errors.material_category_id" x-text="errors.material_category_id"
                                    class="absolute left-0 -bottom-5 text-[10px] text-red-600"></span>
                            </div>

                            {{-- Texture --}}
                            <div class="relative w-full">
                                <select name="material_texture_id" x-model="material_texture_id"
                                    class="appearance-none w-full rounded-md border px-3 py-2 text-sm text-gray-700 {{ $errors->addOrder->has('material_texture_id') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2">
                                    <option value="">-- Select Texture --</option>
                                    @foreach ($materialTextures as $texture)
                                        <option value="{{ $texture->id }}"
                                            {{ old('material_texture_id') == $texture->id ? 'selected' : '' }}>
                                            {{ $texture->texture_name }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Custom Arrow --}}
                                <div
                                    class="pointer-events-none absolute top-1/2 -translate-y-1/2 right-3 flex items-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                {{-- Danger Icon --}}
                                @if ($errors->addOrder->has('material_texture_id'))
                                    <span
                                        class="absolute top-1/2 -translate-y-1/2 right-8 text-red-500 pointer-events-none">
                                        <x-icons.danger />
                                    </span>
                                @endif

                                {{-- Error Message --}}
                                @error('material_texture_id', 'addOrder')
                                    <p class="absolute left-0 -bottom-5 text-[10px] text-red-600">{{ $message }}</p>
                                @enderror
                                <span x-show="errors.material_texture_id" x-text="errors.material_texture_id"
                                    class="absolute left-0 -bottom-5 text-[10px] text-red-600"></span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Notes --}}
                <div class="flex flex-col md:flex-row md:items-start gap-2 md:gap-3">
                    <label class="text-sm text-gray-600 md:w-14">Note</label>
                    <textarea rows="3" name="notes" x-model="notes"
                        class="w-full md:flex-1 min-h-[165px] rounded-md border border-gray-300 px-3 py-2 text-sm
                focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400"
                        placeholder="Write notes here...">{{ old('notes') }}</textarea>
                    @error('notes', 'addOrder')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        {{-- ================= Detail Orders ================= --}}
        <section class="space-y-5 border-b pb-8">
            <h3 class="text-lg font-semibold text-gray-800">Detail Orders</h3>

            {{-- Design Variants List --}}
            <template x-for="(design, dIndex) in designVariants" :key="dIndex">
                <div class="border border-gray-300 rounded-lg p-4 relative">
                    {{-- Delete Design Variant --}}
                    <button type="button" @click="designVariants.splice(dIndex, 1)"
                        class="absolute top-2 right-2 p-2 rounded-md text-gray-500 hover:text-red-600">✕</button>

                    {{-- Design Name & Add Sleeve Variant --}}
                    <div class="flex flex-col md:flex-row gap-3 mt-8 md:mt-0">
                        <input type="text" placeholder="Design Name" x-model="design.name"
                            class="w-full md:w-72 rounded-md border border-gray-300 px-3 py-2 text-sm" />
                        <div @click="addSleeveVariant(dIndex)"
                            class="px-3 py-2 rounded-md border border-gray-300 bg-gray-50 hover:bg-gray-100 text-sm cursor-pointer">
                            + Add Sleeve Variant
                        </div>
                    </div>

                    {{-- Sleeve Variants List --}}
                    <template x-for="(variant, vIndex) in design.sleeveVariants" :key="vIndex">
                        <div class="border border-gray-200 rounded-lg p-4 space-y-4 mt-4 relative">
                            {{-- Delete Sleeve Variant --}}
                            <button type="button" @click="design.sleeveVariants.splice(vIndex, 1)"
                                class="absolute top-2 right-2 p-2 rounded-md text-gray-500 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="flex flex-col md:flex-row md:justify-start md:items-center gap-3">
                                {{-- Sleeve --}}
                                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-8 mt-4 md:mt-0">
                                    <label class="text-sm text-gray-600">Sleeve</label>
                                    <select x-model="variant.sleeve"
                                        class="w-full md:w-56 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">
                                        <option value="">-- Select Sleeve --</option>
                                        <template x-for="s in sleeves" :key="s.id">
                                            <option :value="s.id" x-text="s.sleeve_name"></option>
                                        </template>
                                    </select>
                                </div>

                                {{-- Add Size --}}
                                <button type="button"
                                    @click="openModal = 'addSize'; selectedDesign = dIndex; selectedVariant = vIndex"
                                    class="w-full md:w-auto px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm">
                                    + Add Size
                                </button>
                            </div>

                            {{-- Table --}}
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 text-gray-600">
                                        <tr>
                                            <th class="py-2 px-4 text-left">No</th>
                                            <th class="py-2 px-4 text-left">Size</th>
                                            <th class="py-2 px-4 text-left">Unit Price</th>
                                            <th class="py-2 px-4 text-left">QTY</th>
                                            <th class="py-2 px-4 text-left">Total Price</th>
                                            <th class="py-2 px-4 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(row, rIndex) in variant.rows" :key="rIndex">
                                            <tr class="border-t">
                                                <td class="py-2 px-4" x-text="rIndex+1"></td>
                                                <td class="py-2 px-4" x-text="row.size"></td>
                                                <td class="py-2 px-4">
                                                    <input type="number" x-model="row.unitPrice" placeholder="0"
                                                        class="w-24 rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-green-500 focus:ring-green-200" />
                                                </td>
                                                <td class="py-2 px-4">
                                                    <input type="number" x-model="row.qty" placeholder="0"
                                                        min="1"
                                                        class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-green-500 focus:ring-green-200" />
                                                </td>
                                                <td class="py-2 px-4 font-medium" x-text="row.unitPrice * row.qty">
                                                </td>
                                                <td class="py-2 px-4 text-right">
                                                    <button type="button" @click="variant.rows.splice(rIndex, 1)"
                                                        class="px-2 py-1 rounded-md bg-red-500 text-white hover:bg-red-600 text-sm">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="variant.rows.length === 0">
                                            <td colspan="6" class="py-3 px-4 text-center text-gray-400">
                                                No sizes added yet.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Button Add Design Variant --}}
            <button type="button" @click="addDesignVariant()"
                class="px-3 py-2 rounded-md border border-gray-300 bg-gray-50 hover:bg-gray-100 text-sm">
                + Add Design Variant
            </button>
            {{-- ================= Modal Add Size ================= --}}
            <div x-show="openModal === 'addSize'" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
                <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 space-y-6">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-semibold">Select Sizes</h3>
                        <button type="button" @click="openModal=null"
                            class="text-gray-400 hover:text-gray-600">✕</button>
                    </div>

                    {{-- Size Cards --}}
                    <div class="grid grid-cols-3 gap-3">
                        <template x-for="size in sizes" :key="size.id">
                            <div @click="toggleSize(size)"
                                :class="selectedSizes.find(s => s.id === size.id) ?
                                    'bg-green-100 border-green-500 ring-2 ring-green-400' :
                                    'bg-white hover:bg-gray-50'"
                                class="cursor-pointer rounded-md border px-4 py-3 text-center text-sm font-medium">
                                <span x-text="size.size_name"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Footer --}}
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200">Cancel</button>
                        <button type="button" @click="applySizes"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">Add Size</button>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= Additionals & Final ================= --}}
        <section class="space-y-5 border-b pb-12">
            <h3 class="text-lg font-semibold text-gray-800">Additionals</h3>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Additionals</label>

                    {{-- List Input Additionals --}}
                    <template x-for="(item, index) in additionals" :key="index">
                        <div class="flex flex-col md:flex-row gap-3 mb-3">
                            <select x-model="item.service_id"
                                class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm
                focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">
                                <option value="">-- Select Service --</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                                @endforeach
                            </select>

                            <input type="number" placeholder="Input Price" x-model="item.price"
                                class="w-32 rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-200" />


                            <button type="button" @click="removeAdditional(index)"
                                class="px-3 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 text-sm">
                                Delete
                            </button>
                        </div>
                    </template>

                    {{-- Button Add --}}
                    <button type="button" @click="addAdditional"
                        class=" px-6 py-2 rounded-md border border-gray-300 bg-gray-50 hover:bg-gray-100 text-sm">
                        + Add Additional
                    </button>
                </div>

                {{-- Shipping --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Shipping</label>
                    {{-- Shipping --}}
                    <select name="shipping_id" x-model="shipping_id"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                        <option value="">-- Select Shipping --</option>
                        @foreach ($shippings as $shipping)
                            <option value="{{ $shipping->id }}">{{ $shipping->shipping_name }}</option>
                        @endforeach
                    </select>
                    <span x-show="errors.shipping_id" x-text="errors.shipping_id"
                        class="text-red-500 text-xs mt-1"></span>
                </div>
            </div>
        </section>

        <input type="hidden" name="payload" x-model="jsonPayload">
        {{-- ================= Discount, Final Price & Create ================= --}}
        <div class="flex justify-end mt-6">
            <div class="w-full md:w-1/3 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                    <label class="text-sm text-gray-600 md:w-24">Discount</label>
                    <input type="number" placeholder="Input discount" x-model="discount"
                        class="w-full md:flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm
           focus:border-green-500 focus:ring-green-200" />

                </div>
                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600">Final Price</p>
                    <p class="text-lg font-bold text-gray-900" x-text="'Rp ' + getFinalPrice().toLocaleString('id-ID')">
                    </p>
                </div>
                <button type="submit"
                    class="w-full px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm font-medium">
                    Create Order
                </button>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        function orderForm() {
            return {
                // ================== STATE ==================
                order_date: '',
                deadline: '',
                customer_id: '',
                sales_id: '',
                product_category_id: '',
                product_color: '',
                material_category_id: '',
                material_texture_id: '',
                notes: '',
                discount: '',
                shipping_id: '',

                designVariants: [],
                additionals: [],
                errors: {},
                jsonPayload: '',

                // SIZE & SLEEVE
                sizes: @json($materialSizes),
                sleeves: @json($materialSleeves),

                // MODAL
                openModal: null,
                selectedDesign: null,
                selectedVariant: null,
                selectedSizes: [],

                // ================== INIT ==================
                init() {
                    // Auto-scroll ke error Laravel pertama
                    @if ($errors->addOrder->any())
                        let firstError = "{{ $errors->addOrder->keys()[0] }}";
                        let el = document.querySelector(`[name="${firstError}"]`);
                        if (el) {
                            el.scrollIntoView({
                                behavior: "smooth",
                                block: "center"
                            });
                            el.focus();
                        }
                    @endif
                },

                // ================== DESIGN VARIANTS ==================
                addDesignVariant() {
                    this.designVariants.push({
                        name: '',
                        sleeveVariants: []
                    });
                },
                addSleeveVariant(dIndex) {
                    this.designVariants[dIndex].sleeveVariants.push({
                        sleeve: '',
                        rows: []
                    });
                },

                // ================== SIZE HANDLER ==================
                toggleSize(size) {
                    let exists = this.selectedSizes.find(s => s.id === size.id);
                    this.selectedSizes = exists ?
                        this.selectedSizes.filter(s => s.id !== size.id) : [...this.selectedSizes, size];
                },
                applySizes() {
                    if (this.selectedDesign !== null && this.selectedVariant !== null) {
                        this.selectedSizes.forEach(size => {
                            this.designVariants[this.selectedDesign]
                                .sleeveVariants[this.selectedVariant]
                                .rows.push({
                                    size_id: size.id,
                                    size: size.size_name,
                                    unitPrice: '', // kosong
                                    qty: '' // kosong
                                });
                        });
                        this.selectedSizes = [];
                        this.openModal = null;
                    }
                },

                // ================== ADDITIONALS ==================
                addAdditional() {
                    this.additionals.push({
                        service_id: '',
                        price: ''
                    });
                },
                removeAdditional(index) {
                    this.additionals.splice(index, 1);
                },

                // ================== CALCULATION ==================
                getSubTotal() {
                    let total = 0;

                    // Dari design variants
                    this.designVariants.forEach(design => {
                        design.sleeveVariants.forEach(variant => {
                            variant.rows.forEach(row => {
                                total += (Number(row.unitPrice) || 0) * (Number(row.qty) || 0);
                            });
                        });
                    });

                    // Dari additionals
                    this.additionals.forEach(add => {
                        total += Number(add.price) || 0;
                    });

                    return total;
                },
                getFinalPrice() {
                    return this.getSubTotal() - (Number(this.discount) || 0);
                },
                formatCurrency(value) {
                    if (!value) return '0';
                    return Number(value).toLocaleString('id-ID');
                },

                // ================== VALIDATION ==================
                validate() {
                    this.errors = {};
                    if (!this.order_date) this.errors.order_date = "Order date is required.";
                    if (!this.deadline) this.errors.deadline = "Deadline is required.";
                    if (!this.customer_id) this.errors.customer_id = "Customer is required.";
                    if (!this.sales_id) this.errors.sales_id = "Sales is required.";
                    if (!this.product_category_id) this.errors.product_category_id = "Product is required.";
                    if (!this.product_color) this.errors.product_color = "Product color is required.";
                    if (!this.material_category_id) this.errors.material_category_id = "Material is required.";
                    if (!this.material_texture_id) this.errors.material_texture_id = "Texture is required.";
                    if (!this.shipping_id) this.errors.shipping_id = "Shipping is required.";
                    return Object.keys(this.errors).length === 0;
                },

                // ================== SUBMIT ==================
                submitForm() {
                    if (!this.validate()) {
                        // Scroll ke error Alpine pertama
                        let firstErrorKey = Object.keys(this.errors)[0];
                        let el = document.querySelector(`[name="${firstErrorKey}"]`);
                        if (el) {
                            el.scrollIntoView({
                                behavior: "smooth",
                                block: "center"
                            });
                            el.focus();
                        }
                        return;
                    }

                    const data = {
                        order_date: this.order_date + " 00:00:00",
                        deadline: this.deadline + " 00:00:00",
                        customer_id: this.customer_id,
                        sales_id: this.sales_id,
                        product_category_id: this.product_category_id,
                        product_color: this.product_color,
                        material_category_id: this.material_category_id,
                        material_texture_id: this.material_texture_id,
                        notes: this.notes,
                        discount: this.discount,
                        shipping_id: this.shipping_id,
                        design_variants: this.designVariants,
                        additionals: this.additionals,
                    };

                    this.jsonPayload = JSON.stringify(data);

                    // Create and submit hidden form
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('admin.orders.store') }}";

                    let csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);

                    let hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'payload';
                    hidden.value = this.jsonPayload;
                    form.appendChild(hidden);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
@endpush
