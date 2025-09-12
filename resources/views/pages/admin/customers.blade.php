@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    @php
        $role = auth()->user()?->role;
        if ($role === 'owner') {
            $root = 'Admin';
        } else {
            $root = 'Menu';
        }
    @endphp
    <x-nav-locate :items="[$root, 'Customers']" />

    {{-- konten revenue di bawah --}}
    {{-- Root Alpine State --}}
    <div x-data="{
        openModal: '{{ session('openModal') }}',
        editCustomer: {},
        searchCustomer: '',
        provinces: {{ $provinces->toJson() }},
        cities: {{ $cities->toJson() }},
        selectedProvince: '',
        selectedCity: '',
        addCustomerAddress: '{{ old('address') }}', // supaya kalau gagal validasi tetap isi
    
        get filteredCities() {
            if (!this.selectedProvince) return [];
            return this.cities.filter(c => c.province_id == this.selectedProvince);
        }
    }" class="gap-6">

        {{-- ===================== Customers ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <h2 class="text-xl font-semibold text-gray-900">Customers</h2>

                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto">
                    {{-- Search --}}
                    <div class="w-full md:w-72">
                        <div class="relative">
                            <x-icons.search />
                            <input type="text" x-model="searchCustomer" placeholder="Search Customer"
                                class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                        </div>
                    </div>

                    {{-- Add Customer --}}
                    <button @click="openModal = 'addCustomer'"
                        class="cursor-pointer w-32 whitespace-nowrap px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Customer
                    </button>
                </div>
            </div>

            {{-- Table Customers --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-96 overflow-y-auto">
                    <table class="min-w-[800px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Name</th>
                                <th class="py-2 px-4 text-left">Phone</th>
                                <th class="py-2 px-4 text-left">Province</th>
                                <th class="py-2 px-4 text-left">City</th>
                                <th class="py-2 px-4 text-left">Address</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $index => $customer)
                                <tr class="border-t border-gray-200"
                                    x-show="searchCustomer.length < 3 || '{{ strtolower($customer->name) }}'.includes(searchCustomer.toLowerCase())">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $customer->name }}</td>
                                    <td class="py-2 px-4">{{ $customer->phone }}</td>
                                    <td class="py-2 px-4">{{ $customer->province->name ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $customer->city->name ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $customer->address }}</td>
                                    <td class="py-2 px-4 text-right">
                                        {{-- Tombol Edit --}}
                                        <button @click="openModal='editCustomer'; editCustomer={{ $customer->toJson() }}"
                                            class="cursor-pointer px-3 py-1 rounded-md border border-gray-300 hover:bg-gray-50">
                                            Edit
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')"
                                                class="cursor-pointer px-3 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                onclick="return confirm('Are you sure you want to delete this customer?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                                        No customers found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== MODALS ===================== --}}
        {{-- ========== Add & Edit Customer Modal ========== --}}
        <div x-show="openModal === 'addCustomer'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Customer</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form action="{{ route('admin.customers.store') }}" method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="relative">
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addCustomer->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addCustomer->has('name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('name', 'addCustomer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <div class="relative">
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addCustomer->has('phone') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addCustomer->has('phone'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('phone', 'addCustomer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Province --}}
                    <div class="relative">
                        <select name="province_id" x-model="selectedProvince"
                            class="appearance-none tom-select mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm text-gray-700 disabled:bg-gray-100 disabled:text-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">
                            <option value="">-- Select Province --</option>
                            <template x-for="prov in provinces" :key="prov.id">
                                <option :value="prov.id" x-text="prov.name"></option>
                            </template>
                        </select>

                        <!-- Ikon SVG -->
                        <div class="pointer-events-none absolute top-1 inset-y-0 right-3 flex items-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>


                    {{-- City --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <select name="city_id" x-model="selectedCity" :disabled="!selectedProvince"
                            class="appearance-none tom-select mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm text-gray-700 disabled:bg-gray-100 disabled:text-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">

                            {{-- Kalau belum pilih provinsi --}}
                            <template x-if="!selectedProvince">
                                <option value="">Choose Province First</option>
                            </template>

                            {{-- Kalau sudah pilih provinsi --}}
                            <template x-if="selectedProvince">
                                <option value="">-- Select City --</option>
                            </template>

                            {{-- Daftar kota berdasarkan provinsi --}}
                            <template x-for="city in filteredCities" :key="city.id">
                                <option :value="city.id" x-text="city.name"></option>
                            </template>
                        </select>
                        <!-- Ikon SVG -->
                        <div class="pointer-events-none absolute top-6 inset-y-0 right-3 flex items-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="flex flex-col md:items-start gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Address</label>
                        <textarea rows="3" name="address" x-model="addCustomerAddress"
                            class="w-full md:flex-1 min-h-[120px] rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400"
                            placeholder="Fill the address here..."></textarea>

                        @error('address', 'addCustomer')
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
        <div x-show="openModal === 'editCustomer'" x-cloak x-init="@if (session('openModal') === 'editCustomer' && session('editCustomerId')) editCustomer = {{ \App\Models\Customer::find(session('editCustomerId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">

            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Customer</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>

                <form :action="`/admin/customers/${editCustomer.id}`" method="POST" class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="relative">
                            <input type="text" name="name" :value="editCustomer.name"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editCustomer->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->editCustomer->has('name'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">
                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('name', 'editCustomer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <div class="relative">
                            <input type="text" name="phone" :value="editCustomer.phone"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editCustomer->has('phone') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->editCustomer->has('phone'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">
                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('phone', 'editCustomer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Province --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">Province</label>
                        <select name="province_id" x-model="editCustomer.province_id"
                            class="appearance-none mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm text-gray-700 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">
                            <option value="">-- Select Province --</option>
                            <template x-for="prov in provinces" :key="prov.id">
                                <option :value="prov.id" x-text="prov.name"></option>
                            </template>
                        </select>

                        <!-- Ikon SVG -->
                        <div class="pointer-events-none absolute top-6 inset-y-0 right-3 flex items-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    {{-- City --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <select name="city_id" x-model="editCustomer.city_id" :disabled="!editCustomer.province_id"
                            class="appearance-none mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm text-gray-700 disabled:bg-gray-100 disabled:text-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">

                            {{-- Kalau belum pilih provinsi --}}
                            <template x-if="!editCustomer.province_id">
                                <option value="">Choose Province First</option>
                            </template>

                            {{-- Kalau sudah pilih provinsi --}}
                            <template x-if="editCustomer.province_id">
                                <option value="">-- Select City --</option>
                            </template>

                            {{-- Daftar kota berdasarkan provinsi --}}
                            <template x-for="city in cities.filter(c => c.province_id == editCustomer.province_id)"
                                :key="city.id">
                                <option :value="city.id" x-text="city.name"></option>
                            </template>
                        </select>
                        <!-- Ikon SVG -->
                        <div class="pointer-events-none absolute top-6 inset-y-0 right-3 flex items-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="flex flex-col md:items-start gap-2 md:gap-3">
                        <label class="text-sm text-gray-600 md:w-24">Address</label>
                        <textarea rows="3" name="address" x-model="editCustomer.address"
                            class="w-full md:flex-1 min-h-[120px] rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400"
                            placeholder="Fill the address here..."></textarea>

                        @error('address', 'editCustomer')
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
