@extends('layouts.app')

@section('title', 'Create Orders')

@section('content')

    @php
        $role = auth()->user()?->role;
        $root = $role === 'owner' ? 'Admin' : 'Menu';
    @endphp

    <x-nav-locate :items="[$root, 'Orders', 'Create Order']" />

    <form x-data="orderForm()" @submit="preparePayload" @customer_id-selected.window="customer_id = $event.detail"
        @sales_id-selected.window="sales_id = $event.detail"
        @product_category_id-selected.window="product_category_id = $event.detail"
        @material_category_id-selected.window="material_category_id = $event.detail"
        @material_texture_id-selected.window="material_texture_id = $event.detail"
        @shipping_id-selected.window="shipping_id = $event.detail" method="POST" action="{{ route('admin.orders.store') }}"
        class="bg-white rounded-lg shadow p-6 space-y-8">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div class="relative flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                    <label class="text-sm text-gray-600 md:w-24">Customer</label>

                    <x-select-form name="customer_id" label="Customer" placeholder="-- Select Customer --" :options="$customers"
                        display="name" :old="old('customer_id')" />
                </div>

                <div class="relative flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                    <label class="text-sm text-gray-600 md:w-24">Sales</label>

                    <x-select-form name="sales_id" label="Sales" placeholder="-- Select Sales --" :options="$sales"
                        display="sales_name" :old="old('sales_id')" />
                </div>


            </div>
        </section>

        {{-- ================= Detail Products ================= --}}
        <section class="space-y-5 border-b pb-12">
            <h3 class="text-lg font-semibold text-gray-800">Detail Products</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div class="space-y-7">
                    {{-- Product --}}
                    <div class="relative flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Product</label>

                        <x-select-form name="product_category_id" label="Product" placeholder="-- Select Product --"
                            :options="$productCategories" display="product_name" :old="old('product_category_id')" />
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
                    <div class="relative flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Material</label>
                        <div class="flex flex-col md:flex-row gap-2 gap-y-6 md:gap-3 w-full">
                            <x-select-form name="material_category_id" label="Product" placeholder="-- Select Material --"
                                :options="$materialCategories" display="material_name" :old="old('material_category_id')" />

                            <x-select-form name="material_texture_id" label="Product" placeholder="-- Select Texture --"
                                :options="$materialTextures" display="texture_name" :old="old('material_texture_id')" />
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
                    <button type="button"
                        @click="
                            let msg = design.name.trim() 
                                ? `Are you sure you want to delete ${design.name} design?` 
                                : 'Are you sure you want to delete this design variant?';
                            if (confirm(msg)) designVariants.splice(dIndex, 1)
                        "
                        class="absolute top-2 right-2 p-2 rounded-md text-gray-500 hover:text-red-600">
                        ✕
                    </button>


                    {{-- Design Name & Add Sleeve Variant --}}
                    <div class="flex flex-col md:flex-row gap-3 mt-8 md:mt-0">
                        <input type="text" placeholder="Design Name" x-model="design.name"
                            class="w-full md:w-72 rounded-md border border-gray-300 px-3 py-2 text-sm" />

                        <div class="flex gap-2 items-center">
                            <button type="button" @click="if(design.name.trim() !== '') addSleeveVariant(dIndex)"
                                :class="design.name.trim() === '' ?
                                    'cursor-not-allowed bg-gray-300 text-white' :
                                    'bg-green-500 hover:bg-green-700 text-white'"
                                class="px-3 py-2 rounded-md border text-sm">
                                + Add Sleeve
                            </button>
                            <span class="italic text-xs text-gray-400">(Fill design name first)</span>
                        </div>
                    </div>


                    {{-- Sleeve Variants List --}}
                    <template x-for="(variant, vIndex) in design.sleeveVariants" :key="vIndex">
                        <div class="border border-gray-200 rounded-lg p-4 space-y-4 mt-4 relative">
                            {{-- Delete Sleeve Variant --}}
                            <button type="button"
                                @click="
                                    if (confirm('Are you sure you want to delete this sleeve?')) {
                                        design.sleeveVariants.splice(vIndex, 1)
                                    }
                                "
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
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        @click="if(variant.sleeve !== '') { openModal = 'addSize'; selectedDesign = dIndex; selectedVariant = vIndex }"
                                        :class="variant.sleeve === '' ?
                                            'cursor-not-allowed bg-gray-300 text-white' :
                                            'bg-green-600 hover:bg-green-700 text-white'"
                                        class="w-full md:w-auto px-3 py-2 rounded-md text-sm">
                                        + Add Size
                                    </button>
                                    <span class="italic text-xs text-gray-400">(Select sleeve variant first)</span>
                                </div>
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
                                                    <input type="number" x-model="row.unitPrice" min="0"
                                                        @focus="if(row.unitPrice == 0) row.unitPrice = ''"
                                                        @blur="if(row.unitPrice === '' || row.unitPrice === null) row.unitPrice = 0"
                                                        class="w-24 rounded-md border border-gray-300 px-2 py-1 text-sm" />
                                                </td>
                                                <td class="py-2 px-4">
                                                    <input type="number" x-model="row.qty" min="0"
                                                        @focus="if(row.qty == 0) row.qty = ''"
                                                        @blur="if(row.qty === '' || row.qty === null) row.qty = 0"
                                                        class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm" />
                                                </td>
                                                <td class="py-2 px-4 font-medium" x-text="row.unitPrice * row.qty">
                                                </td>
                                                <td class="py-2 px-4 text-right">
                                                    <button type="button" @click="variant.rows.splice(rIndex, 1)"
                                                        class="px-2 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 text-sm">
                                                        <x-icons.trash class="text-white" />
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
                class="px-3 py-2 rounded-md text-sm font-medium cursor-pointer bg-primary hover:bg-green-700 text-white">
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
                                class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">
                                <option value="">-- Select Service --</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                                @endforeach
                            </select>
                            <input type="number" x-model="item.price" min="0"
                                @focus="if(item.price == 0) item.price = ''"
                                @blur="if(item.price === '' || item.price === null) item.price = 0"
                                class="w-32 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400" />

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
                <div class="relative flex flex-col gap-2 md:gap-3">
                    <label class="text-sm text-gray-600 md:w-24">Shipping</label>

                    <x-select-form name="shipping_id" label="Shipping" placeholder="-- Select Shipping --"
                        :options="$shippings" display="shipping_name" :old="old('shipping_id')" />
                </div>

            </div>
        </section>

        {{-- ================= Discount, Final Price & Create ================= --}}
        <div class="flex justify-end mt-6">
            <div class="w-full md:w-1/3 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                    <label class="text-sm text-gray-600 md:w-24">Discount</label>
                    <input type="number" x-model="discount" min="0" @focus="if(discount == 0) discount = ''"
                        @blur="if(discount === '' || discount=== null) discount = 0"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400" />

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

        <input type="hidden" name="payload" x-model="jsonPayload">

    </form>
@endsection
@push('scripts')
    <script>
        function searchSelect(options, oldValue, fieldName) {
            return {
                open: false,
                search: '',
                options,
                selected: null,
                selectedId: oldValue || '',
                fieldName,

                init() {
                    if (this.selectedId) {
                        this.selected = this.options.find(o => String(o.id) === String(this.selectedId)) || null;
                    }
                    // ⬇️ dispatch event biar orderForm tahu
                    this.$dispatch(`${this.fieldName}-selected`, this.selectedId || '');
                },

                select(option) {
                    this.selected = option;
                    this.selectedId = option.id;
                    this.open = false;

                    // ⬇️ dispatch lagi setiap kali select berubah
                    this.$dispatch(`${this.fieldName}-selected`, this.selectedId);
                }
            }
        }

        function orderForm() {
            return {
                // ====== STATE UTAMA ======
                order_date: '{{ old('order_date') }}',
                deadline: '{{ old('deadline') }}',
                customer_id: '{{ old('customer_id') }}',
                sales_id: '{{ old('sales_id') }}',
                product_category_id: '{{ old('product_category_id') }}',
                product_color: '{{ old('product_color') }}',
                material_category_id: '{{ old('material_category_id') }}',
                material_texture_id: '{{ old('material_texture_id') }}',
                notes: '{{ old('notes') }}',
                discount: {{ old('discount', 0) }},
                shipping_id: '{{ old('shipping_id') }}',

                // ====== DETAIL (pakai old payload kalau ada) ======
                designVariants: [],
                additionals: [],
                jsonPayload: '',

                // REF DATA
                sizes: @json($materialSizes),
                sleeves: @json($materialSleeves),

                // MODAL STATE
                openModal: null,
                selectedDesign: null,
                selectedVariant: null,
                selectedSizes: [],

                // INIT
                init() {
                    @if (old('payload'))
                        try {
                            let oldData = JSON.parse(@json(old('payload')));
                            this.designVariants = oldData.design_variants || [];
                            this.additionals = oldData.additionals || [];
                        } catch (e) {
                            console.error("Failed to parse old payload", e);
                        }
                    @endif
                },

                // ====== DESIGN VARIANT HANDLER ======
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

                // SIZE HANDLER
                toggleSize(size) {
                    let exists = this.selectedSizes.find(s => s.id === size.id);
                    this.selectedSizes = exists ?
                        this.selectedSizes.filter(s => s.id !== size.id) : [...this.selectedSizes, size];
                },
                applySizes() {
                    // 1. Pastikan ada design & variant yang sedang dipilih
                    if (this.selectedDesign !== null && this.selectedVariant !== null) {

                        // 2. Loop semua size yang dipilih di modal (this.selectedSizes)
                        this.selectedSizes.forEach(size => {

                            // 3. Cek apakah size ini sudah ada di rows (untuk variant tsb)
                            let exists = this.designVariants[this.selectedDesign]
                                .sleeveVariants[this.selectedVariant]
                                .rows.find(r => r.size_id === size.id);

                            // 4. Kalau belum ada, push data baru ke rows
                            if (!exists) {
                                this.designVariants[this.selectedDesign]
                                    .sleeveVariants[this.selectedVariant]
                                    .rows.push({
                                        size_id: size.id,
                                        size: size.size_name,
                                        unitPrice: 0,
                                        qty: 0
                                    });
                            }
                        });

                        // 5. Reset pilihan & tutup modal
                        this.selectedSizes = [];
                        this.openModal = null;
                    }
                },

                // ADDITIONALS
                addAdditional() {
                    this.additionals.push({
                        service_id: '',
                        price: 0
                    });
                },
                removeAdditional(index) {
                    this.additionals.splice(index, 1);
                },

                // CALCULATION
                getSubTotal() {
                    let total = 0;
                    this.designVariants.forEach(design => {
                        design.sleeveVariants.forEach(variant => {
                            variant.rows.forEach(row => {
                                total += (row.unitPrice || 0) * (row.qty || 0);
                            });
                        });
                    });
                    this.additionals.forEach(add => {
                        total += parseInt(add.price || 0);
                    });
                    return total;
                },
                getFinalPrice() {
                    return this.getSubTotal() - (this.discount || 0);
                },

                // SUBMIT → cukup update payload sebelum form submit
                preparePayload() {
                    const data = {
                        order_date: this.order_date ? this.order_date + " 00:00:00" : null,
                        deadline: this.deadline ? this.deadline + " 00:00:00" : null,
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
                }
            }
        }
    </script>
@endpush
