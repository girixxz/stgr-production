@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    @php
        $role = auth()->user()?->role;
        if ($role === 'owner') {
            $root = 'Admin';
        } else {
            $root = 'Menu';
        }
    @endphp

    <x-nav-locate :items="[$root, 'Orders']" />

    {{-- Root Alpine State --}}
    <div x-data="{
        activeFilter: '{{ request('filter', 'default') }}',
        searchQuery: '{{ request('search', '') }}',
        showCalendar: false,
        showDateOptions: false,
        selectedDateRange: '{{ request('date_range', '') }}',
        startDate: '{{ request('start_date', '') }}',
        endDate: '{{ request('end_date', '') }}',
        showDetailModal: false,
        showDeleteModal: false,
        selectedOrder: null,
        deleteForm: null
    }" class="space-y-5">

        {{-- Filter & Actions Section --}}
        <div class="bg-white border border-gray-200 rounded-md p-5">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                {{-- Filter Tabs --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.orders.index', ['filter' => 'default'] + request()->except('filter')) }}"
                        @click="activeFilter = 'default'"
                        :class="activeFilter === 'default' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Default
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'pending'] + request()->except('filter')) }}"
                        @click="activeFilter = 'pending'"
                        :class="activeFilter === 'pending' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Pending
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'dp'] + request()->except('filter')) }}"
                        @click="activeFilter = 'dp'"
                        :class="activeFilter === 'dp' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        DP
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'wip'] + request()->except('filter')) }}"
                        @click="activeFilter = 'wip'"
                        :class="activeFilter === 'wip' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        WIP
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'finished'] + request()->except('filter')) }}"
                        @click="activeFilter = 'finished'"
                        :class="activeFilter === 'finished' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Finished
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'cancelled'] + request()->except('filter')) }}"
                        @click="activeFilter = 'cancelled'"
                        :class="activeFilter === 'cancelled' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Cancelled
                    </a>
                </div>

                {{-- Search & Actions --}}
                <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                    {{-- Calendar Filter Button --}}
                    <div class="relative">
                        <button type="button" @click="showDateOptions = !showDateOptions"
                            class="w-full px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Filter Date</span>
                        </button>

                        {{-- Date Options Dropdown --}}
                        <div x-show="showDateOptions" @click.away="showDateOptions = false" x-cloak
                            class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-20">
                            <a href="{{ route('admin.orders.index', ['date_range' => 'yesterday'] + request()->except(['date_range', 'start_date', 'end_date'])) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-lg">
                                Yesterday
                            </a>
                            <a href="{{ route('admin.orders.index', ['date_range' => 'today'] + request()->except(['date_range', 'start_date', 'end_date'])) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Today
                            </a>
                            <a href="{{ route('admin.orders.index', ['date_range' => 'last_7_days'] + request()->except(['date_range', 'start_date', 'end_date'])) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Last 7 Days
                            </a>
                            <a href="{{ route('admin.orders.index', ['date_range' => 'last_30_days'] + request()->except(['date_range', 'start_date', 'end_date'])) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Last 30 Days
                            </a>
                            <a href="{{ route('admin.orders.index', ['date_range' => 'this_month'] + request()->except(['date_range', 'start_date', 'end_date'])) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                This Month
                            </a>
                            <button type="button" @click="showCalendar = true; showDateOptions = false"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 last:rounded-b-lg border-t border-gray-100">
                                Custom Date
                            </button>
                        </div>
                    </div>

                    {{-- Search --}}
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex-1 lg:w-72">
                        <input type="hidden" name="filter" :value="activeFilter">
                        @if (request('date_range'))
                            <input type="hidden" name="date_range" value="{{ request('date_range') }}">
                        @elseif(request('start_date') || request('end_date'))
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        @endif
                        <div class="relative">
                            <x-icons.search />
                            <input type="text" name="search" x-model="searchQuery" value="{{ request('search') }}"
                                placeholder="Search invoice, customer..."
                                class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" />
                        </div>
                    </form>

                    {{-- Create Order Button --}}
                    <a href="{{ route('admin.orders.create') }}"
                        class="cursor-pointer whitespace-nowrap px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark text-sm text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Order
                    </a>
                </div>
            </div>

            {{-- Calendar Filter (Hidden by default) --}}
            <div x-show="showCalendar" x-transition x-cloak class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="hidden" name="filter" :value="activeFilter">
                    <input type="hidden" name="search" :value="searchQuery">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" x-model="startDate" value="{{ request('start_date') }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" x-model="endDate" value="{{ request('end_date') }}"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark text-sm">
                            Apply
                        </button>
                        <a href="{{ route('admin.orders.index', ['filter' => request('filter', 'default')]) }}"
                            class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 text-sm">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="bg-white border border-gray-200 rounded-md p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-primary-light text-font-base z-10">
                        <tr>
                            <th class="py-3 px-4 text-left font-medium rounded-l-md">Invoice No</th>
                            <th class="py-3 px-4 text-left font-medium">Customer</th>
                            <th class="py-3 px-4 text-left font-medium">Product</th>
                            <th class="py-3 px-4 text-right font-medium">QTY</th>
                            @if (request('filter', 'default') === 'default')
                                <th class="py-3 px-4 text-right font-medium">Total Bill</th>
                                <th class="py-3 px-4 text-right font-medium">Remaining</th>
                            @endif
                            <th class="py-3 px-4 text-left font-medium">Order Date</th>
                            <th class="py-3 px-4 text-left font-medium">Deadline</th>
                            <th class="py-3 px-4 text-center font-medium">Status</th>
                            <th class="py-3 px-4 text-center font-medium rounded-r-md">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <span class="font-medium text-primary">
                                        {{ $order->invoice?->invoice_no ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-medium text-gray-900">{{ $order->customer->customer_name }}</span>
                                        <span class="text-xs text-gray-500">{{ $order->customer->phone }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-900">{{ $order->productCategory->product_name }}</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <span class="font-medium">{{ number_format($order->total_qty) }} pcs</span>
                                </td>
                                @if (request('filter', 'default') === 'default')
                                    <td class="py-3 px-4 text-right">
                                        <span class="font-medium text-gray-900">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="font-medium text-alert-danger">
                                            Rp {{ number_format($order->invoice?->amount_due ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                @endif
                                <td class="py-3 px-4">
                                    <span class="text-gray-600">
                                        {{ $order->order_date->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-gray-600">
                                        {{ $order->deadline->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span @class([
                                        'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium',
                                        'bg-yellow-100 text-yellow-800' => $order->production_status === 'pending',
                                        'bg-blue-100 text-blue-800' => $order->production_status === 'wip',
                                        'bg-green-100 text-green-800' => $order->production_status === 'finished',
                                        'bg-red-100 text-red-800' => $order->production_status === 'cancelled',
                                    ])>
                                        {{ ucfirst($order->production_status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" type="button"
                                            class="p-2 rounded-md hover:bg-gray-100 text-gray-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-cloak
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                            <button type="button"
                                                @click="selectedOrder = {{ json_encode([
                                                    'id' => $order->id,
                                                    'invoice_no' => $order->invoice?->invoice_no ?? '-',
                                                    'customer_name' => $order->customer->customer_name,
                                                    'customer_phone' => $order->customer->phone,
                                                    'customer_address' => $order->customer->address,
                                                    'sales_name' => $order->sale->sales_name,
                                                    'product_name' => $order->productCategory->product_name,
                                                    'product_color' => $order->product_color,
                                                    'material_name' => $order->materialCategory->material_name,
                                                    'texture_name' => $order->materialTexture->texture_name,
                                                    'shipping_name' => $order->shipping->shipping_name,
                                                    'total_qty' => $order->total_qty,
                                                    'subtotal' => $order->subtotal,
                                                    'discount' => $order->discount,
                                                    'grand_total' => $order->grand_total,
                                                    'order_date' => $order->order_date->format('d M Y'),
                                                    'deadline' => $order->deadline->format('d M Y'),
                                                    'production_status' => ucfirst($order->production_status),
                                                    'notes' => $order->notes ?? '-',
                                                    'priority' => ucfirst($order->priority),
                                                ]) }}; showDetailModal = true; open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>Detail</span>
                                            </button>
                                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span>Edit</span>
                                            </a>
                                            <button type="button"
                                                @click="deleteForm = '{{ route('admin.orders.destroy', $order->id) }}'; showDeleteModal = true; open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium">No orders found</p>
                                        <p class="text-sm mt-1">Try adjusting your filter or search criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($orders->hasPages())
                <div class="mt-5 pt-5 border-t border-gray-200 flex justify-center">
                    <div class="pagination-wrapper">
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Detail Modal --}}
        <div x-show="showDetailModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="showDetailModal = false"
                class="bg-white rounded-xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                {{-- Header --}}
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Order Detail</h3>
                    <button @click="showDetailModal = false" type="button"
                        class="text-gray-400 hover:text-gray-600 text-2xl leading-none">
                        &times;
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-6" x-show="selectedOrder">
                    {{-- Invoice & Status --}}
                    <div class="grid grid-cols-2 gap-4 pb-4 border-b">
                        <div>
                            <p class="text-sm text-gray-500">Invoice Number</p>
                            <p class="font-semibold text-primary" x-text="selectedOrder?.invoice_no"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Priority</p>
                            <p class="font-semibold" x-text="selectedOrder?.priority"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Order Date</p>
                            <p class="font-medium" x-text="selectedOrder?.order_date"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Deadline</p>
                            <p class="font-medium" x-text="selectedOrder?.deadline"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Production Status</p>
                            <p class="font-medium" x-text="selectedOrder?.production_status"></p>
                        </div>
                    </div>

                    {{-- Customer Info --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Customer Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="font-medium" x-text="selectedOrder?.customer_name"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Phone</p>
                                <p class="font-medium" x-text="selectedOrder?.customer_phone"></p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500">Address</p>
                                <p class="font-medium" x-text="selectedOrder?.customer_address"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Sales Person</p>
                                <p class="font-medium" x-text="selectedOrder?.sales_name"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Product Info --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Product Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Product</p>
                                <p class="font-medium" x-text="selectedOrder?.product_name"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Color</p>
                                <p class="font-medium" x-text="selectedOrder?.product_color"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Material</p>
                                <p class="font-medium" x-text="selectedOrder?.material_name"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Texture</p>
                                <p class="font-medium" x-text="selectedOrder?.texture_name"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Shipping</p>
                                <p class="font-medium" x-text="selectedOrder?.shipping_name"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Quantity</p>
                                <p class="font-medium" x-text="selectedOrder?.total_qty + ' pcs'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Pricing</h4>
                        <div class="space-y-2 bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium"
                                    x-text="'Rp ' + Number(selectedOrder?.subtotal).toLocaleString('id-ID')"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount</span>
                                <span class="font-medium text-red-600"
                                    x-text="'- Rp ' + Number(selectedOrder?.discount).toLocaleString('id-ID')"></span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-300">
                                <span class="font-semibold text-gray-900">Grand Total</span>
                                <span class="font-bold text-primary"
                                    x-text="'Rp ' + Number(selectedOrder?.grand_total).toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div x-show="selectedOrder?.notes && selectedOrder?.notes !== '-'">
                        <h4 class="font-semibold text-gray-900 mb-2">Notes</h4>
                        <p class="text-gray-700 bg-gray-50 rounded-lg p-3" x-text="selectedOrder?.notes"></p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                    <button @click="showDetailModal = false" type="button"
                        class="px-4 py-2 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div x-show="showDeleteModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="showDeleteModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-md">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">Delete Order</h3>
                    <p class="text-center text-gray-600 mb-6">
                        Are you sure you want to delete this order? This action cannot be undone.
                    </p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" type="button"
                            class="flex-1 px-4 py-2 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <form :action="deleteForm" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Pagination Custom Styles */
        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-wrapper .flex {
            gap: 0.5rem;
        }

        /* Pagination links */
        .pagination-wrapper a,
        .pagination-wrapper span {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        /* Active page */
        .pagination-wrapper span[aria-current="page"] {
            background-color: #56ba9f !important;
            color: white !important;
            font-weight: 500;
        }

        /* Inactive pages */
        .pagination-wrapper a {
            color: #4b5563 !important;
            background-color: #f3f4f6 !important;
        }

        .pagination-wrapper a:hover {
            background-color: #e5e7eb !important;
            color: #1f2937 !important;
        }

        /* Navigation arrows (Previous/Next) */
        .pagination-wrapper a[rel="prev"],
        .pagination-wrapper a[rel="next"] {
            color: #56ba9f !important;
            background-color: #f0fdf4 !important;
            border: 1px solid #56ba9f;
        }

        .pagination-wrapper a[rel="prev"]:hover,
        .pagination-wrapper a[rel="next"]:hover {
            background-color: #56ba9f !important;
            color: white !important;
        }

        /* Disabled arrows */
        .pagination-wrapper span[aria-disabled="true"] {
            color: #d1d5db !important;
            background-color: #f9fafb !important;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
@endpush
