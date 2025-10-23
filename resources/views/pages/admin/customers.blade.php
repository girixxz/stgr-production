@extends('layouts.app')
@section('title', 'Manage Customers')
@section('content')

    <x-nav-locate :items="['Menu', 'Customers']" />

    {{-- Root Alpine State --}}
    <div x-data="{
        openModal: '{{ session('openModal') }}',
        detailCustomer: {},
        editCustomer: {},
        searchCustomer: '',
        // For Add Customer
        addProvince: '{{ old('province_id') }}',
        addCity: '{{ old('city_id') }}',
        addDistrict: '{{ old('district_id') }}',
        addVillage: '{{ old('village_id') }}',
        addCities: [],
        addDistricts: [],
        addVillages: [],
        // For Edit Customer
        editProvince: '',
        editCity: '',
        editDistrict: '',
        editVillage: '',
        editCities: [],
        editDistricts: [],
        editVillages: [],
    
        async init() {
            // Watch for modal changes and scroll to modal
            this.$watch('openModal', value => {
                if (value) {
                    setTimeout(() => {
                        const modalEl = document.querySelector('[x-show=\'openModal === \\\'' + value + '\\\'\']');
                        if (modalEl) {
                            modalEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }, 100);
                }
            });
    
            // Restore Add Customer state from old input
            // Convert to integer because API returns integer IDs
            const oldProvince = '{{ old('province_id') }}' ? parseInt('{{ old('province_id') }}') : '';
            const oldCity = '{{ old('city_id') }}' ? parseInt('{{ old('city_id') }}') : '';
            const oldDistrict = '{{ old('district_id') }}' ? parseInt('{{ old('district_id') }}') : '';
            const oldVillage = '{{ old('village_id') }}' ? parseInt('{{ old('village_id') }}') : '';
    
            console.log('🔍 OLD VALUES:', {
                province: oldProvince,
                city: oldCity,
                district: oldDistrict,
                village: oldVillage
            });
    
            if (oldProvince) {
                this.addProvince = oldProvince;
                await this.fetchCities(oldProvince, 'add', true);
    
                if (oldCity) {
                    // Wait for cities to be populated
                    await new Promise(resolve => setTimeout(resolve, 100));
                    this.addCity = oldCity;
                    console.log('✅ City set to:', oldCity, 'Available cities:', this.addCities.length);
                    await this.fetchDistricts(oldCity, 'add', true);
    
                    if (oldDistrict) {
                        // Wait for districts to be populated
                        await new Promise(resolve => setTimeout(resolve, 100));
                        this.addDistrict = oldDistrict;
                        console.log('✅ District set to:', oldDistrict, 'Available districts:', this.addDistricts.length);
                        await this.fetchVillages(oldDistrict, 'add', true);
    
                        if (oldVillage) {
                            // Wait for villages to be populated
                            await new Promise(resolve => setTimeout(resolve, 100));
                            this.addVillage = oldVillage;
                            console.log('✅ Village set to:', oldVillage, 'Available villages:', this.addVillages.length);
                        }
                    }
                }
            }
        },
        async fetchCities(provinceId, mode = 'add', preserveValue = false) {
            if (!provinceId) {
                if (mode === 'add') {
                    this.addCities = [];
                    this.addDistricts = [];
                    this.addVillages = [];
                    this.addCity = '';
                    this.addDistrict = '';
                    this.addVillage = '';
                } else {
                    this.editCities = [];
                    this.editDistricts = [];
                    this.editVillages = [];
                    this.editCity = '';
                    this.editDistrict = '';
                    this.editVillage = '';
                }
                return;
            }
            try {
                const response = await fetch(`{{ url('/admin/customers/api/cities') }}/${provinceId}`);
                const cities = await response.json();
                if (mode === 'add') {
                    this.addCities = cities;
                    if (!preserveValue) {
                        this.addDistricts = [];
                        this.addVillages = [];
                        this.addCity = '';
                        this.addDistrict = '';
                        this.addVillage = '';
                    }
                } else {
                    this.editCities = cities;
                    if (!preserveValue) {
                        this.editDistricts = [];
                        this.editVillages = [];
                        this.editCity = '';
                        this.editDistrict = '';
                        this.editVillage = '';
                    }
                }
            } catch (error) {
                console.error('Error fetching cities:', error);
            }
        },
    
        async fetchDistricts(cityId, mode = 'add', preserveValue = false) {
            if (!cityId) {
                if (mode === 'add') {
                    this.addDistricts = [];
                    this.addVillages = [];
                    this.addDistrict = '';
                    this.addVillage = '';
                } else {
                    this.editDistricts = [];
                    this.editVillages = [];
                    this.editDistrict = '';
                    this.editVillage = '';
                }
                return;
            }
            try {
                const response = await fetch(`{{ url('/admin/customers/api/districts') }}/${cityId}`);
                const districts = await response.json();
                if (mode === 'add') {
                    this.addDistricts = districts;
                    if (!preserveValue) {
                        this.addVillages = [];
                        this.addDistrict = '';
                        this.addVillage = '';
                    }
                } else {
                    this.editDistricts = districts;
                    if (!preserveValue) {
                        this.editVillages = [];
                        this.editDistrict = '';
                        this.editVillage = '';
                    }
                }
            } catch (error) {
                console.error('Error fetching districts:', error);
            }
        },
    
        async fetchVillages(districtId, mode = 'add', preserveValue = false) {
            if (!districtId) {
                if (mode === 'add') {
                    this.addVillages = [];
                    this.addVillage = '';
                } else {
                    this.editVillages = [];
                    this.editVillage = '';
                }
                return;
            }
            try {
                const response = await fetch(`{{ url('/admin/customers/api/villages') }}/${districtId}`);
                const villages = await response.json();
                if (mode === 'add') {
                    this.addVillages = villages;
                    if (!preserveValue) {
                        this.addVillage = '';
                    }
                } else {
                    this.editVillages = villages;
                    if (!preserveValue) {
                        this.editVillage = '';
                    }
                }
            } catch (error) {
                console.error('Error fetching villages:', error);
            }
        },
    
        async loadEditLocationData() {
            if (this.editCustomer.province_id) {
                this.editProvince = this.editCustomer.province_id;
                await this.fetchCities(this.editCustomer.province_id, 'edit');
            }
            if (this.editCustomer.city_id) {
                this.editCity = this.editCustomer.city_id;
                await this.fetchDistricts(this.editCustomer.city_id, 'edit');
            }
            if (this.editCustomer.district_id) {
                this.editDistrict = this.editCustomer.district_id;
                await this.fetchVillages(this.editCustomer.district_id, 'edit');
            }
            if (this.editCustomer.village_id) {
                this.editVillage = this.editCustomer.village_id;
            }
        }
    }" class="grid grid-cols-1">

        {{-- ===================== CUSTOMERS ===================== --}}
        <section class="bg-white border border-gray-200 rounded-lg p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <h2 class="text-xl font-semibold text-gray-900 flex-shrink-0">Customers</h2>

                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="relative flex-1 md:w-72">
                        <x-icons.search />
                        <input type="text" x-model="searchCustomer" placeholder="Search Customer"
                            class="w-full rounded-md border border-gray-200 pl-9 pr-3 py-2 text-sm text-gray-700
                                  focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" />
                    </div>

                    {{-- Add Customer --}}
                    <button @click="openModal = 'addCustomer'"
                        class="cursor-pointer flex-shrink-0 w-40 whitespace-nowrap px-3 py-2 rounded-md bg-primary text-white hover:bg-primary-dark text-sm text-center">
                        + Add Customer
                    </button>
                </div>
            </div>

            {{-- Table Customers --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-168 overflow-y-auto">
                    <table class="min-w-[900px] w-full text-sm">
                        <thead class="sticky top-0 bg-primary-light text-font-base z-10">
                            <tr>
                                <th class="py-2 px-4 text-left rounded-l-md">No</th>
                                <th class="py-2 px-4 text-left">Customer</th>
                                <th class="py-2 px-4 text-left">Total Order</th>
                                <th class="py-2 px-4 text-left">Total QTY</th>
                                <th class="py-2 px-4 text-left">Address</th>
                                <th class="py-2 px-4 text-right rounded-r-md">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr class="border-t border-gray-200"
                                    x-show="
                                        '{{ strtolower($customer->customer_name) }} {{ strtolower($customer->phone ?? '') }}'
                                        .includes(searchCustomer.toLowerCase())
                                    ">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ $customer->customer_name }}</span>
                                            <span class="text-xs text-gray-500">{{ $customer->phone ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-4">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-primary-light text-primary-dark">
                                            {{ $customer->orders_count ?? 0 }} Orders
                                        </span>
                                    </td>
                                    <td class="py-2 px-4">
                                        <span class="font-medium">{{ number_format($customer->orders_sum_total_qty ?? 0) }}
                                            pcs</span>
                                    </td>
                                    <td class="py-2 px-4">
                                        <div class="text-xs max-w-xs">
                                            @if ($customer->address || $customer->village || $customer->district || $customer->city || $customer->province)
                                                {{ $customer->address ? $customer->address . ', ' : '' }}
                                                {{ $customer->village ? $customer->village->village_name . ', ' : '' }}
                                                {{ $customer->district ? $customer->district->district_name . ', ' : '' }}
                                                {{ $customer->city ? $customer->city->city_name . ', ' : '' }}
                                                {{ $customer->province ? $customer->province->province_name : '' }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-2 px-4 text-right">
                                        <div class="relative inline-block text-left" x-data="{
                                            open: false,
                                            dropdownStyle: {},
                                            checkPosition() {
                                                const button = this.$refs.button;
                                                const rect = button.getBoundingClientRect();
                                                const spaceBelow = window.innerHeight - rect.bottom;
                                                const spaceAbove = rect.top;
                                                const dropUp = spaceBelow < 200 && spaceAbove > spaceBelow;
                                        
                                                if (dropUp) {
                                                    this.dropdownStyle = {
                                                        position: 'fixed',
                                                        top: (rect.top - 130) + 'px',
                                                        left: (rect.right - 160) + 'px',
                                                        width: '160px'
                                                    };
                                                } else {
                                                    this.dropdownStyle = {
                                                        position: 'fixed',
                                                        top: (rect.bottom + 8) + 'px',
                                                        left: (rect.right - 160) + 'px',
                                                        width: '160px'
                                                    };
                                                }
                                            }
                                        }"
                                            x-init="$watch('open', value => {
                                                if (value) {
                                                    const scrollContainer = $el.closest('.overflow-y-auto');
                                                    const mainContent = document.querySelector('main');
                                                    const closeOnScroll = () => { open = false; };
                                            
                                                    scrollContainer?.addEventListener('scroll', closeOnScroll);
                                                    mainContent?.addEventListener('scroll', closeOnScroll);
                                                    window.addEventListener('resize', closeOnScroll);
                                                }
                                            })">
                                            {{-- Tombol Titik 3 Horizontal --}}
                                            <button x-ref="button" @click="checkPosition(); open = !open" type="button"
                                                class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Actions">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>

                                            {{-- Dropdown Menu with Fixed Position --}}
                                            <div x-show="open" @click.away="open = false" x-transition
                                                :style="dropdownStyle"
                                                class="rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[9999]">
                                                <div class="py-1">
                                                    {{-- Detail --}}
                                                    <button
                                                        @click="detailCustomer = {{ $customer->toJson() }}; openModal = 'detailCustomer'; open = false"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Detail
                                                    </button>

                                                    {{-- Edit --}}
                                                    <button
                                                        @click="editCustomer = {{ $customer->toJson() }}; openModal = 'editCustomer'; loadEditLocationData(); open = false"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </button>

                                                    {{-- Delete --}}
                                                    <form action="{{ route('admin.customers.destroy', $customer) }}"
                                                        method="POST" class="inline w-full"
                                                        onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 text-sm text-alert-danger hover:bg-gray-100 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-3 px-4 text-center text-red-500 border-t border-gray-200">
                                        No Customers found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== MODAL DETAIL CUSTOMER ===================== --}}
        <div x-show="openModal === 'detailCustomer'" x-transition.opacity x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/50 backdrop-blur-sm">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="openModal = ''" class="bg-white rounded-xl shadow-lg w-full max-w-md">
                    {{-- Header --}}
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Customer Detail</h3>
                        <button @click="openModal = ''" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                            ✕
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-5 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500">Customer Name</p>
                            <p class="text-sm font-medium" x-text="detailCustomer.customer_name || '-'"></p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="text-sm" x-text="detailCustomer.phone || '-'"></p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Province</p>
                            <p class="text-sm" x-text="detailCustomer.province?.province_name || '-'"></p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">City</p>
                            <p class="text-sm" x-text="detailCustomer.city?.city_name || '-'"></p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">District</p>
                            <p class="text-sm" x-text="detailCustomer.district?.district_name || '-'"></p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Village</p>
                            <p class="text-sm" x-text="detailCustomer.village?.village_name || '-'"></p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Address</p>
                            <p class="text-sm" x-text="detailCustomer.address || '-'"></p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Total Orders</p>
                            <p class="text-sm font-medium">
                                <span x-text="detailCustomer.orders_count || 0"></span> Orders
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Total Quantity</p>
                            <p class="text-sm font-medium">
                                <span x-text="detailCustomer.orders_sum_total_qty || 0"></span> pcs
                            </p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex justify-end gap-3 p-5 border-t border-gray-200">
                        <button @click="openModal = ''"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== MODAL ADD CUSTOMER ===================== --}}
        <div x-show="openModal === 'addCustomer'" x-transition.opacity x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/50 backdrop-blur-sm">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="openModal = ''" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                    {{-- Header --}}
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Add Customer</h3>
                        <button @click="openModal = ''" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                            ✕
                        </button>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('admin.customers.store') }}" method="POST">
                        @csrf
                        <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
                            {{-- Customer Name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Customer Name <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                    @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->addCustomer->has(
                                            'customer_name'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->addCustomer->has(
                                            'customer_name'),
                                    ]) />
                                @error('customer_name', 'addCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->addCustomer->has(
                                            'phone'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->addCustomer->has(
                                            'phone'),
                                    ]) />
                                @error('phone', 'addCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Province --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                <select x-model="addProvince" name="province_id"
                                    @change="fetchCities(addProvince, 'add')" @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->addCustomer->has(
                                            'province_id'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->addCustomer->has(
                                            'province_id'),
                                    ])>
                                    <option value="">Select Province</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                            {{ $province->province_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province_id', 'addCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- City (shown when province selected) --}}
                            <div x-show="addProvince" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <select x-model="addCity" name="city_id" @change="fetchDistricts(addCity, 'add')"
                                    @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->addCustomer->has(
                                            'city_id'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->addCustomer->has(
                                            'city_id'),
                                    ])>
                                    <option value="">Select City</option>
                                    <template x-for="city in addCities" :key="city.id">
                                        <option :value="city.id" x-text="city.city_name"></option>
                                    </template>
                                </select>
                                @error('city_id', 'addCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- District (shown when city selected) --}}
                            <div x-show="addCity" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                <select x-model="addDistrict" name="district_id"
                                    @change="fetchVillages(addDistrict, 'add')" @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->addCustomer->has(
                                            'district_id'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->addCustomer->has(
                                            'district_id'),
                                    ])>
                                    <option value="">Select District</option>
                                    <template x-for="district in addDistricts" :key="district.id">
                                        <option :value="district.id" x-text="district.district_name"></option>
                                    </template>
                                </select>
                                @error('district_id', 'addCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Village (shown when district selected) --}}
                            <div x-show="addDistrict" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Village</label>
                                <select x-model="addVillage" name="village_id" @class([
                                    'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                    'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->addCustomer->has(
                                        'village_id'),
                                    'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->addCustomer->has(
                                        'village_id'),
                                ])>
                                    <option value="">Select Village</option>
                                    <template x-for="village in addVillages" :key="village.id">
                                        <option :value="village.id" x-text="village.village_name"></option>
                                    </template>
                                </select>
                                @error('village_id', 'addCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address Detail</label>
                                <textarea name="address" rows="3" @class([
                                    'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                    'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->addCustomer->has(
                                        'address'),
                                    'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->addCustomer->has(
                                        'address'),
                                ])>{{ old('address') }}</textarea>
                                @error('address', 'addCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex justify-end gap-3 p-5 border-t border-gray-200">
                            <button type="button" @click="openModal = ''"
                                class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark cursor-pointer">
                                Add Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===================== MODAL EDIT CUSTOMER ===================== --}}
        <div x-show="openModal === 'editCustomer'" x-transition.opacity x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/50 backdrop-blur-sm">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="openModal = ''" class="bg-white rounded-xl shadow-lg w-full max-w-2xl">
                    {{-- Header --}}
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Customer</h3>
                        <button @click="openModal = ''" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                            ✕
                        </button>
                    </div>

                    {{-- Form --}}
                    <form :action="`{{ route('admin.customers.index') }}/${editCustomer.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
                            {{-- Customer Name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Customer Name <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="customer_name" x-model="editCustomer.customer_name"
                                    :value="editCustomer.customer_name" @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->editCustomer->has(
                                            'customer_name'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->editCustomer->has(
                                            'customer_name'),
                                    ]) />
                                @error('customer_name', 'editCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="phone" x-model="editCustomer.phone"
                                    :value="editCustomer.phone" @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->editCustomer->has(
                                            'phone'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->editCustomer->has(
                                            'phone'),
                                    ]) />
                                @error('phone', 'editCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Province --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                <select x-model="editProvince" name="province_id"
                                    @change="fetchCities(editProvince, 'edit')" @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->editCustomer->has(
                                            'province_id'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->editCustomer->has(
                                            'province_id'),
                                    ])>
                                    <option value="">Select Province</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">
                                            {{ $province->province_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province_id', 'editCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- City (shown when province selected) --}}
                            <div x-show="editProvince && editCities.length > 0" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <select x-model="editCity" name="city_id" @change="fetchDistricts(editCity, 'edit')"
                                    @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->editCustomer->has(
                                            'city_id'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->editCustomer->has(
                                            'city_id'),
                                    ])>
                                    <option value="">Select City</option>
                                    <template x-for="city in editCities" :key="city.id">
                                        <option :value="city.id" x-text="city.city_name"></option>
                                    </template>
                                </select>
                                @error('city_id', 'editCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- District (shown when city selected) --}}
                            <div x-show="editCity && editDistricts.length > 0" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                <select x-model="editDistrict" name="district_id"
                                    @change="fetchVillages(editDistrict, 'edit')" @class([
                                        'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                        'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->editCustomer->has(
                                            'district_id'),
                                        'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->editCustomer->has(
                                            'district_id'),
                                    ])>
                                    <option value="">Select District</option>
                                    <template x-for="district in editDistricts" :key="district.id">
                                        <option :value="district.id" x-text="district.district_name"></option>
                                    </template>
                                </select>
                                @error('district_id', 'editCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Village (shown when district selected) --}}
                            <div x-show="editDistrict && editVillages.length > 0" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Village</label>
                                <select x-model="editVillage" name="village_id" @class([
                                    'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                    'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->editCustomer->has(
                                        'village_id'),
                                    'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->editCustomer->has(
                                        'village_id'),
                                ])>
                                    <option value="">Select Village</option>
                                    <template x-for="village in editVillages" :key="village.id">
                                        <option :value="village.id" x-text="village.village_name"></option>
                                    </template>
                                </select>
                                @error('village_id', 'editCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address Detail</label>
                                <textarea name="address" rows="3" x-model="editCustomer.address" @class([
                                    'w-full rounded-md px-4 py-2 text-sm border focus:outline-none focus:ring-2 text-gray-700',
                                    'border-red-500 focus:border-red-500 focus:ring-red-200' => $errors->editCustomer->has(
                                        'address'),
                                    'border-gray-200 focus:border-primary focus:ring-primary/20' => !$errors->editCustomer->has(
                                        'address'),
                                ])></textarea>
                                @error('address', 'editCustomer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex justify-end gap-3 p-5 border-t border-gray-200">
                            <button type="button" @click="openModal = ''"
                                class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark cursor-pointer">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection
