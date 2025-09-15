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
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
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
    <div x-show="openModal === 'addSize'" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
        <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 space-y-6">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-semibold">Select Sizes</h3>
                <button type="button" @click="openModal=null" class="text-gray-400 hover:text-gray-600">✕</button>
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
