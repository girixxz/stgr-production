@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    @php
        $role = auth()->user()?->role;
        $root = $role === 'owner' ? 'Admin' : 'Menu';
    @endphp

    <x-nav-locate :items="[$root, 'Orders']" />

    {{-- Root Alpine State --}}
    <div x-data="{
        activeFilter: '{{ request('filter', 'default') }}',
        searchQuery: '{{ request('search') }}',
        startDate: '{{ $startDate ?? '' }}',
        endDate: '{{ $endDate ?? '' }}',
        dateRange: '{{ $dateRange ?? '' }}',
        showDateFilter: false,
        showDateCustomRange: false,
        datePreset: '',
        showCancelConfirm: null,
        getDateLabel() {
            if (this.dateRange === 'last_month') return 'Bulan Lalu';
            if (this.dateRange === 'last_7_days') return '1 Minggu Yang Lalu';
            if (this.dateRange === 'yesterday') return 'Yesterday';
            if (this.dateRange === 'today') return 'Today';
            if (this.dateRange === 'this_month') return 'This Month';
            if (this.dateRange === 'custom' && this.startDate && this.endDate) return 'Custom Date';
            return 'Date';
        },
        applyDatePreset(preset) {
            const today = new Date();
            const form = this.$refs.dateForm;
            if (preset === 'last-month') {
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
                this.startDate = lastMonth.toISOString().split('T')[0];
                this.endDate = lastMonthEnd.toISOString().split('T')[0];
                this.dateRange = 'last_month';
                form.querySelector('input[name=date_range]').value = 'last_month';
                form.querySelector('input[name=start_date]').value = this.startDate;
                form.querySelector('input[name=end_date]').value = this.endDate;
                form.submit();
            } else if (preset === '1-week-ago') {
                const oneWeekAgo = new Date(today);
                oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
                this.startDate = oneWeekAgo.toISOString().split('T')[0];
                this.endDate = today.toISOString().split('T')[0];
                this.dateRange = 'last_7_days';
                form.querySelector('input[name=date_range]').value = 'last_7_days';
                form.querySelector('input[name=start_date]').value = this.startDate;
                form.querySelector('input[name=end_date]').value = this.endDate;
                form.submit();
            } else if (preset === 'yesterday') {
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                this.startDate = yesterday.toISOString().split('T')[0];
                this.endDate = yesterday.toISOString().split('T')[0];
                this.dateRange = 'yesterday';
                form.querySelector('input[name=date_range]').value = 'yesterday';
                form.querySelector('input[name=start_date]').value = this.startDate;
                form.querySelector('input[name=end_date]').value = this.endDate;
                form.submit();
            } else if (preset === 'today') {
                this.startDate = today.toISOString().split('T')[0];
                this.endDate = today.toISOString().split('T')[0];
                this.dateRange = 'today';
                form.querySelector('input[name=date_range]').value = 'today';
                form.querySelector('input[name=start_date]').value = this.startDate;
                form.querySelector('input[name=end_date]').value = this.endDate;
                form.submit();
            } else if (preset === 'this-month') {
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                this.startDate = firstDay.toISOString().split('T')[0];
                this.endDate = lastDay.toISOString().split('T')[0];
                this.dateRange = 'this_month';
                form.querySelector('input[name=date_range]').value = 'this_month';
                form.querySelector('input[name=start_date]').value = this.startDate;
                form.querySelector('input[name=end_date]').value = this.endDate;
                form.submit();
            } else if (preset === 'custom') {
                this.showDateCustomRange = true;
            }
        }
    }" class="space-y-6">

        {{-- ================= SECTION 1: STATISTICS CARDS ================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Orders --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_orders']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total QTY --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total QTY</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_qty']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Bill --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Bill</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp
                            {{ number_format($stats['total_bill'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Remaining Due --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Remaining Due</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Rp
                            {{ number_format($stats['remaining_due'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($stats['pending']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- WIP --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">WIP</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($stats['wip']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Finished --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Finished</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['finished']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Cancelled --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Cancelled</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['cancelled']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= SECTION 2: FILTER & ACTIONS ================= --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                {{-- Left: Filter Buttons --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.orders.index', ['filter' => 'default'] + request()->except('filter')) }}"
                        :class="activeFilter === 'default' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Default
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'pending'] + request()->except('filter')) }}"
                        :class="activeFilter === 'pending' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Pending
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'wip'] + request()->except('filter')) }}"
                        :class="activeFilter === 'wip' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        WIP
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'finished'] + request()->except('filter')) }}"
                        :class="activeFilter === 'finished' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Finished
                    </a>
                    <a href="{{ route('admin.orders.index', ['filter' => 'cancelled'] + request()->except('filter')) }}"
                        :class="activeFilter === 'cancelled' ? 'bg-primary text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Cancelled
                    </a>
                </div>

                {{-- Right: Search, Date Filter, Create Button --}}
                <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex-1 lg:w-64"
                        x-ref="searchForm">
                        <input type="hidden" name="filter" value="{{ request('filter', 'default') }}">
                        @if (request('start_date'))
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        @endif
                        @if (request('end_date'))
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        @endif
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                @input.debounce.500ms="$refs.searchForm.submit()"
                                placeholder="Search invoice, customer..."
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </form>

                    {{-- Date Filter --}}
                    <div class="relative">
                        <button type="button" @click="showDateFilter = !showDateFilter"
                            :class="dateRange ? 'border-primary bg-primary/5 text-primary' :
                                'border-gray-300 text-gray-700 bg-white'"
                            class="px-4 py-2 border rounded-md text-sm font-medium hover:bg-gray-50 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span x-text="getDateLabel()"></span>
                        </button>

                        {{-- Hidden Form for Date Presets --}}
                        <form x-ref="dateForm" method="GET" action="{{ route('admin.orders.index') }}"
                            class="hidden">
                            <input type="hidden" name="filter" :value="activeFilter">
                            <input type="hidden" name="search" :value="searchQuery">
                            <input type="hidden" name="date_range" :value="dateRange">
                            <input type="hidden" name="start_date" :value="startDate">
                            <input type="hidden" name="end_date" :value="endDate">
                        </form>

                        {{-- Date Filter Modal --}}
                        <div x-show="showDateFilter" @click.away="showDateFilter = false; showDateCustomRange = false"
                            x-cloak
                            class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-20">

                            {{-- Main Preset Options --}}
                            <div x-show="!showDateCustomRange" class="p-2">
                                <button @click="applyDatePreset('last-month')" type="button"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    Bulan Lalu
                                </button>
                                <button @click="applyDatePreset('1-week-ago')" type="button"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    1 Minggu Yang Lalu
                                </button>
                                <button @click="applyDatePreset('yesterday')" type="button"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    Yesterday
                                </button>
                                <button @click="applyDatePreset('today')" type="button"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    Today
                                </button>
                                <button @click="applyDatePreset('this-month')" type="button"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                    This Month
                                </button>
                                <div class="border-t border-gray-200 my-2"></div>
                                <button @click="applyDatePreset('custom')" type="button"
                                    class="w-full text-left px-4 py-2.5 text-sm font-medium text-primary hover:bg-primary/5 rounded-md transition-colors">
                                    Custom Date
                                </button>
                            </div>

                            {{-- Custom Range Form --}}
                            <form x-show="showDateCustomRange" method="GET" action="{{ route('admin.orders.index') }}"
                                class="p-4" @submit="dateRange = 'custom'">
                                <input type="hidden" name="filter" :value="activeFilter">
                                <input type="hidden" name="search" :value="searchQuery">
                                <input type="hidden" name="date_range" value="custom">

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                        <input type="date" name="start_date" x-model="startDate"
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                        <input type="date" name="end_date" x-model="endDate"
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    </div>
                                    <div class="flex gap-2 pt-2">
                                        <button type="submit"
                                            class="flex-1 px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary-dark">
                                            Apply
                                        </button>
                                        <button type="button" @click="showDateCustomRange = false"
                                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">
                                            Back
                                        </button>
                                    </div>
                                    <a href="{{ route('admin.orders.index', ['filter' => request('filter', 'default')]) }}"
                                        class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200 text-center">
                                        Reset Filter
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Create Order Button --}}
                    <a href="{{ route('admin.orders.create') }}"
                        class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark text-sm font-medium flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Order
                    </a>
                </div>
            </div>
        </div>

        {{-- ================= SECTION 3: TABLE ================= --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="py-3 px-4 text-left font-medium">No Invoice</th>
                            <th class="py-3 px-4 text-left font-medium">Customer</th>
                            <th class="py-3 px-4 text-left font-medium">Product</th>
                            <th class="py-3 px-4 text-left font-medium">QTY</th>
                            <th class="py-3 px-4 text-left font-medium">Total Bill</th>
                            <th class="py-3 px-4 text-left font-medium">Remaining</th>
                            <th class="py-3 px-4 text-left font-medium">Order Date</th>
                            <th class="py-3 px-4 text-left font-medium">Deadline</th>
                            <th class="py-3 px-4 text-left font-medium">Status</th>
                            <th class="py-3 px-4 text-center font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50">
                                {{-- Invoice No with Priority --}}
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="font-medium text-gray-900">{{ $order->invoice->invoice_no ?? '-' }}</span>
                                        @if (isset($order->priority) && strtolower($order->priority) === 'high')
                                            <span class="text-xs text-red-600 italic font-medium">(HIGH)</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Customer --}}
                                <td class="py-3 px-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $order->customer->customer_name ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $order->customer->phone ?? '-' }}</p>
                                    </div>
                                </td>

                                {{-- Product --}}
                                <td class="py-3 px-4">
                                    <span class="text-gray-700">{{ $order->productCategory->product_name ?? '-' }}</span>
                                </td>

                                {{-- QTY --}}
                                <td class="py-3 px-4">
                                    <span class="font-medium text-gray-900">{{ $order->orderItems->sum('qty') }}</span>
                                </td>

                                {{-- Total Bill --}}
                                <td class="py-3 px-4">
                                    <span class="font-medium text-gray-900">Rp
                                        {{ number_format($order->invoice->total_bill ?? 0, 0, ',', '.') }}</span>
                                </td>

                                {{-- Remaining --}}
                                <td class="py-3 px-4">
                                    <span class="font-medium text-orange-600">Rp
                                        {{ number_format($order->invoice->amount_due ?? 0, 0, ',', '.') }}</span>
                                </td>

                                {{-- Order Date --}}
                                <td class="py-3 px-4">
                                    <span
                                        class="text-gray-700">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</span>
                                </td>

                                {{-- Deadline --}}
                                <td class="py-3 px-4">
                                    <span
                                        class="text-gray-700">{{ $order->deadline ? \Carbon\Carbon::parse($order->deadline)->format('d M Y') : '-' }}</span>
                                </td>

                                {{-- Status --}}
                                <td class="py-3 px-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'wip' => 'bg-blue-100 text-blue-800',
                                            'finished' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusClass =
                                            $statusClasses[$order->production_status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ strtoupper($order->production_status) }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td class="py-3 px-4">
                                    <div class="flex justify-center">
                                        <div class="relative inline-block text-left" x-data="{
                                            open: false,
                                            dropdownStyle: {},
                                            checkPosition() {
                                                const button = this.$refs.button;
                                                const rect = button.getBoundingClientRect();
                                                const spaceBelow = window.innerHeight - rect.bottom;
                                                const spaceAbove = rect.top;
                                                const dropUp = spaceBelow < 200 && spaceAbove > spaceBelow;
                                        
                                                // Position fixed dropdown
                                                if (dropUp) {
                                                    this.dropdownStyle = {
                                                        position: 'fixed',
                                                        top: (rect.top - 140) + 'px',
                                                        left: (rect.right - 180) + 'px',
                                                        width: '180px'
                                                    };
                                                } else {
                                                    this.dropdownStyle = {
                                                        position: 'fixed',
                                                        top: (rect.bottom + 8) + 'px',
                                                        left: (rect.right - 180) + 'px',
                                                        width: '180px'
                                                    };
                                                }
                                            }
                                        }"
                                            x-init="$watch('open', value => {
                                                if (value) {
                                                    const scrollContainer = $el.closest('.overflow-x-auto');
                                                    const mainContent = document.querySelector('main');
                                                    const closeOnScroll = () => { open = false; };
                                            
                                                    scrollContainer?.addEventListener('scroll', closeOnScroll);
                                                    mainContent?.addEventListener('scroll', closeOnScroll);
                                                    window.addEventListener('resize', closeOnScroll);
                                                }
                                            })">
                                            {{-- Three Dot Button HORIZONTAL --}}
                                            <button x-ref="button" @click="checkPosition(); open = !open" type="button"
                                                class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Actions">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>

                                            {{-- Dropdown Menu with Fixed Position --}}
                                            <div x-show="open" @click.away="open = false" x-cloak :style="dropdownStyle"
                                                class="bg-white border border-gray-200 rounded-md shadow-lg z-50 py-1">
                                                {{-- View Detail --}}
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View Detail
                                                </a>

                                                {{-- Edit (Hidden for finished/cancelled) --}}
                                                @if ($order->production_status !== 'finished' && $order->production_status !== 'cancelled')
                                                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endif

                                                {{-- Cancel (Hidden for cancelled) --}}
                                                @if ($order->production_status !== 'cancelled')
                                                    <button type="button"
                                                        @click="showCancelConfirm = {{ $order->id }}; open = false"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Cancel Order
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm">No orders found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination with Info (Always Show) --}}
            <div id="pagination-section" class="mt-5 pt-5 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    {{-- Center: Showing info --}}
                    <div class="flex-1 flex justify-start text-sm text-gray-600">
                        @if ($orders->total() > 0)
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }}
                            results
                        @else
                            No results found
                        @endif
                    </div>

                    {{-- Right: Pagination --}}
                    <div class="flex items-center">
                        @if ($orders->hasPages())
                            {{ $orders->links() }}
                        @else
                            <div class="pagination">
                                <span class="px-3 py-2 text-sm text-gray-500 bg-gray-50 rounded-md">
                                    Page 1 of 1
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= CANCEL CONFIRMATION MODAL ================= --}}
        <div x-show="showCancelConfirm !== null" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center"
            style="background-color: rgba(0, 0, 0, 0.5);">
            <div @click.away="showCancelConfirm = null"
                class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                {{-- Icon --}}
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
                    Cancel Order?
                </h3>

                {{-- Message --}}
                <p class="text-sm text-gray-600 text-center mb-6">
                    Are you sure you want to cancel this order? This action cannot be undone and the order status will
                    be changed to <span class="font-semibold text-red-600">Cancelled</span>.
                </p>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button type="button" @click="showCancelConfirm = null"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        No, Keep Order
                    </button>
                    <form :action="'{{ route('admin.orders.index') }}/' + showCancelConfirm + '/cancel'" method="POST"
                        class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                            Yes, Cancel Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        /* Flowbite Pagination with Custom Primary Color #56ba9f */

        /* Active page - Primary Green Background with HIGH specificity */
        nav[aria-label="Pagination Navigation"] ul li span.pagination-active-page {
            background-color: #56ba9f !important;
            border-color: #56ba9f !important;
            color: white !important;
        }

        nav[aria-label="Pagination Navigation"] ul li span.pagination-active-page:hover {
            background-color: #489984 !important;
            border-color: #489984 !important;
            color: white !important;
        }

        /* Ensure pagination buttons have consistent styling */
        nav[aria-label="Pagination Navigation"] ul li span,
        nav[aria-label="Pagination Navigation"] ul li a {
            min-width: 2rem;
            font-weight: 500;
        }

        /* Hover effect for links */
        nav[aria-label="Pagination Navigation"] ul li a:hover {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
        }

        /* Force active state if still not working */
        span[aria-current="page"] {
            background-color: #56ba9f !important;
            border-color: #56ba9f !important;
            color: white !important;
        }
    </style>
@endpush
