@extends('layouts.app')

@section('title', 'Create Orders')

@section('content')
    @php
        $role = auth()->user()?->role;
        $root = $role === 'owner' ? 'Admin' : 'Menu';
    @endphp

    <x-nav-locate :items="[$root, 'Orders', 'Create Order']" />
    <form method="POST" action="{{ route('admin.orders.store') }}" x-data="orderForm()" @submit.prevent="submitForm">
        @csrf

        <div class="bg-white rounded-lg shadow p-6 space-y-8">

            {{-- ================= Header ================= --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-8">
                <h2 class="text-xl font-semibold text-gray-900">Create Order</h2>
                <div class="flex md:flex-row gap-x-4 md:gap-x-6 gap-y-4 w-full md:w-auto">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3 w-full md:w-auto">
                        <label for="order_date" class="text-sm text-gray-600 md:w-18">Order Date</label>
                        <div class="flex flex-col w-full md:w-40">
                            <input id="order_date" type="date" x-model="order_date"
                                class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400" />
                            <span x-show="errors.order_date" x-text="errors.order_date"
                                class="text-red-500 text-xs mt-1"></span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3 w-full md:w-auto">
                        <label for="deadline" class="text-sm text-gray-600 md:w-18">Deadline</label>
                        <input id="deadline" type="date" x-model="deadline"
                            class="w-full md:w-40 rounded-md border border-gray-300 px-3 py-2 text-sm 
                        focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400" />
                        <span x-show="errors.deadline" x-text="errors.deadline" class="text-red-500 text-xs mt-1"></span>
                    </div>

                </div>
            </div>

            {{-- ================= Customers & Sales ================= --}}
            <section class="space-y-5 border-b pb-12">
                <h3 class="text-lg font-semibold text-gray-800">Data Customers & Sales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">

                    <div>
                        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                            <label class="text-sm text-gray-600 md:w-24">Customer</label>
                            <select x-model="customer_id"
                                class="w-full md:flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">
                                <option value="">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <span x-show="errors.deadline" x-text="errors.customer_id"
                                class="text-red-500 text-xs mt-1"></span>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                            <label class="text-sm text-gray-600 md:w-24">Sales</label>
                            <select name="sales_id" x-model="sales_id"
                                class="w-full md:flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
                                <option value="">-- Select Sales --</option>
                                @foreach ($sales as $sale)
                                    <option value="{{ $sale->id }}">{{ $sale->sales_name }}</option>
                                @endforeach
                            </select>

                            <span x-show="errors.sales_id" x-text="errors.deadline"
                                class="text-red-500 text-xs mt-1"></span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ================= Detail Products ================= --}}
            <section class="space-y-5 border-b pb-12">
                <h3 class="text-lg font-semibold text-gray-800">Detail Products</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div class="space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                            <label class="text-sm text-gray-600 md:w-24">Product</label>
                            <select name="product_category_id" x-model="product_category_id"
                                class="w-full md:flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
                                <option value="">-- Select Product --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>

                            <span x-show="errors.product_category_id" x-text="errors.product_category_id"
                                class="text-red-500 text-xs mt-1"></span>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                            <label class="text-sm text-gray-600 md:w-24">Color</label>
                            <input type="text" placeholder="Enter color" x-model="product_color"
                                class="w-full md:flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm
                            focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400" />
                            <span x-show="errors.product_color" x-text="errors.product_color"
                                class="text-red-500 text-xs mt-1"></span>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                            <label class="text-sm text-gray-600 md:w-24">Materials</label>
                            <div class="flex flex-col md:flex-row gap-2 md:gap-3 flex-1">
                                {{-- Material --}}
                                <select name="material_category_id" x-model="material_category_id"
                                    class="w-full md:flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <option value="">-- Select Material --</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">{{ $material->material_name }}</option>
                                    @endforeach
                                </select>
                                <span x-show="errors.material_category_id" x-text="errors.material_category_id"
                                    class="text-red-500 text-xs mt-1"></span>
                                {{-- Texture --}}
                                <select name="material_texture_id" x-model="material_texture_id"
                                    class="w-full md:flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <option value="">-- Select Texture --</option>
                                    @foreach ($textures as $texture)
                                        <option value="{{ $texture->id }}">{{ $texture->texture_name }}</option>
                                    @endforeach
                                </select>
                                <span x-show="errors.material_texture_id" x-text="errors.material_texture_id"
                                    class="text-red-500 text-xs mt-1"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-start gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Note</label>
                        <textarea rows="3" x-model="notes"
                            class="w-full md:flex-1 min-h-[145px] rounded-md border border-gray-300 px-3 py-2 text-sm
                        focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400"
                            placeholder="Write notes here..."></textarea>
                    </div>
                </div>
            </section>

            {{-- ================= Detail Orders ================= --}}
            <section class="space-y-5 border-b pb-8" x-data="orderDetail()">
                <h3 class="text-lg font-semibold text-gray-800">Detail Orders</h3>

                {{-- Design Variants List --}}
                <template x-for="(design, dIndex) in designVariants" :key="dIndex">
                    <div class="border border-gray-300 rounded-lg p-4 relative">
                        {{-- Delete Design Variant --}}
                        <button type="button" @click="designVariants.splice(dIndex, 1)"
                            class="absolute top-2 right-2 p-2 rounded-md text-gray-500 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        {{-- Design Name & Add Sleeve Variant --}}
                        <div class="flex flex-col md:flex-row gap-3 mt-8 md:mt-0">
                            <input type="text" placeholder="Design Name" x-model="design.name"
                                class="w-full md:w-72 rounded-md border border-gray-300 px-3 py-2 text-sm 
                    focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400" />
                            <div @click="addSleeveVariant(dIndex)"
                                class="px-3 py-2 rounded-md border border-gray-300 bg-gray-50 hover:bg-gray-100 text-sm">
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
                                                        <input type="number" x-model="row.unitPrice" min="0"
                                                            class="w-24 rounded-md border border-gray-300 px-2 py-1 text-sm" />
                                                    </td>
                                                    <td class="py-2 px-4">
                                                        <input type="number" x-model="row.qty" min="1"
                                                            class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm" />
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
                <button type="button" @click="addDesignVariant"
                    class="px-3 py-2 rounded-md border border-gray-300 bg-gray-50 hover:bg-gray-100 text-sm">
                    + Add Design Variant
                </button>

                {{-- ================= Modal Add Size ================= --}}
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
            <section class="space-y-5 border-b pb-12" x-data="additionalsData()">
                <h3 class="text-lg font-semibold text-gray-800">Additionals</h3>
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Additionals</label>

                        {{-- List Input Additionals --}}
                        <template x-for="(item, index) in additionals" :key="index">
                            <div class="flex flex-col md:flex-row gap-3 mb-3">
                                <select x-model="item.name"
                                    class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm
                            focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">
                                    <input type="number" placeholder="Input Price" x-model="item.price"
                                        class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm
                        focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400" />

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
                        <span x-show="errors.shipping" x-text="errors.shipping" class="text-red-500 text-xs mt-1"></span>
                    </div>
                </div>
            </section>

            {{-- ================= Discount, Final Price & Create ================= --}}
            <input type="hidden" name="payload" x-model="jsonPayload">
            <div class="flex justify-end mt-6">
                <div class="w-full md:w-1/3 space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Discount</label>
                        <input type="number" placeholder="Enter discount" x-model="discount"
                            class="w-full md:flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm
                        focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400" />
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-600">Final Price</p>
                        <p class="text-lg font-bold text-gray-900"
                            x-text="'Rp ' + getFinalPrice().toLocaleString('id-ID')"></p>
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm font-medium">
                        Create Order
                    </button>
                </div>
            </div>
        </div>
        <script>
            function orderForm() {
                return {
                    // STATE
                    order_date: '',
                    deadline: '',
                    customer_id: '',
                    sales_id: '',
                    product_category_id: '',
                    product_color: '',
                    material_category_id: '',
                    material_texture_id: '',
                    notes: '',
                    discount: 0,
                    shipping_id: '',
                    designVariants: [],
                    additionals: [],
                    jsonPayload: '',
                    errors: {},


                    // SIZE MODAL STATE
                    sizes: @json($sizes), // data ukuran dari DB
                    sleeves: @json($sleeves),

                    openModal: null,
                    selectedDesign: null,
                    selectedVariant: null,
                    selectedSizes: [],

                    // DESIGN VARIANTS
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
                        const exists = this.selectedSizes.find(s => s.id === size.id);
                        if (exists) {
                            this.selectedSizes = this.selectedSizes.filter(s => s.id !== size.id);
                        } else {
                            this.selectedSizes.push(size);
                        }
                    },
                    applySizes() {
                        if (this.selectedDesign !== null && this.selectedVariant !== null) {
                            this.selectedSizes.forEach(size => {
                                this.designVariants[this.selectedDesign]
                                    .sleeveVariants[this.selectedVariant]
                                    .rows.push({
                                        size_id: size.id,
                                        size: size.size_name,
                                        unitPrice: 0,
                                        qty: 1
                                    });
                            });
                            this.selectedSizes = [];
                            this.openModal = null;
                        }
                    },

                    // ADDITIONALS
                    addAdditional() {
                        this.additionals.push({
                            name: '',
                            price: 0
                        });
                    },
                    removeAdditional(index) {
                        this.additionals.splice(index, 1);
                    },

                    getSubTotal() {
                        let total = 0;

                        // hitung designVariants
                        this.designVariants.forEach(design => {
                            design.sleeveVariants.forEach(variant => {
                                variant.rows.forEach(row => {
                                    total += (row.unitPrice || 0) * (row.qty || 0);
                                });
                            });
                        });

                        // hitung additionals
                        this.additionals.forEach(add => {
                            total += parseInt(add.price || 0);
                        });

                        return total;
                    },
                    getFinalPrice() {
                        return this.getSubTotal() - (this.discount || 0);
                    },

                    // SUBMIT
                    submitForm() {
                        this.errors = {}; // reset errors

                        if (!this.order_date) this.errors.order_date = "Order date is required.";
                        if (!this.deadline) this.errors.deadline = "Deadline is required.";
                        if (!this.customer_id) this.errors.customer_id = "Customer is required.";
                        if (!this.sales_id) this.errors.sales_id = "Sales is required.";
                        if (!this.product_category_id) this.errors.product_category_id = "Product is required.";
                        if (!this.product_color) this.errors.product_color = "Product color is required.";
                        if (!this.material_category_id) this.errors.material_category_id = "Material is required.";
                        if (!this.material_texture_id) this.errors.material_texture_id = "Texture is required.";
                        if (!this.shipping_id) this.errors.shipping_id = "Shipping is required.";

                        // kalau ada error jangan submit
                        if (Object.keys(this.errors).length > 0) {
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

    </form>
@endsection
