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

    <div x-data="{
        search: '',
        filterDate: '',
        filterStatus: '',
        filterPayment: ''
    }" class="bg-white border border-gray-200 rounded-2xl p-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-center">
            <h2 class="text-xl font-semibold text-gray-900">Orders</h2>

            <div class="md:ml-auto flex flex-col md:flex-row gap-2 w-full md:w-auto">
                {{-- Search --}}
                <div class="relative w-full md:w-64">
                    <x-icons.search />
                    <input type="text" x-model="search" placeholder="Search Orders"
                        class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                </div>

                {{-- Filter Date --}}
                <input type="date" x-model="filterDate"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />

                {{-- Filter Status --}}
                <select x-model="filterStatus"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="done">Done</option>
                </select>

                {{-- Filter Payment --}}
                <select x-model="filterPayment"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300">
                    <option value="">All Payment</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="credit">Credit</option>
                </select>

                {{-- Create Order --}}
                <a href="{{ route('admin.orders.create') }}"
                    class="cursor-pointer whitespace-nowrap px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm">
                    + Create Order
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="mt-5 overflow-x-auto">
            <div class="max-h-[500px] overflow-y-auto">
                <table class="min-w-[1000px] w-full text-sm">
                    <thead class="sticky top-0 bg-white text-gray-600 z-10">
                        <tr>
                            <th class="py-2 px-4 text-left">No</th>
                            <th class="py-2 px-4 text-left">No Invoice</th>
                            <th class="py-2 px-4 text-left">Customer</th>
                            <th class="py-2 px-4 text-left">Product Name</th>
                            <th class="py-2 px-4 text-left">Payment</th>
                            <th class="py-2 px-4 text-left">Total</th>
                            <th class="py-2 px-4 text-left">Progress</th>
                            <th class="py-2 px-4 text-left">Created</th>
                            <th class="py-2 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (range(1, 10) as $i)
                            @php
                                $status = ['pending', 'processing', 'done'][array_rand([0, 1, 2])];
                                $payment = ['cash', 'transfer', 'credit'][array_rand([0, 1, 2])];
                            @endphp
                            <tr class="border-t border-gray-200"
                                x-show="
                                    '{{ $i }} INV00{{ $i }} Customer{{ $i }} Product{{ $i }} {{ $payment }} {{ $status }}'
                                    .toLowerCase()
                                    .includes(search.toLowerCase()) &&
                                    (filterStatus === '' || '{{ $status }}' === filterStatus)
&&
                                    (filterPayment === '' || '{{ $payment }}' === filterPayment)
                                ">
                                <td class="py-2 px-4">{{ $i }}</td>
                                <td class="py-2 px-4">INV00{{ $i }}</td>
                                <td class="py-2 px-4">Customer {{ $i }}</td>
                                <td class="py-2 px-4">Product {{ $i }}</td>
                                <td class="py-2 px-4 capitalize">{{ $payment }}</td>
                                <td class="py-2 px-4">Rp{{ number_format($i * 10000, 0, ',', '.') }}</td>
                                <td class="py-2 px-4">
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium
                                        {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($status === 'processing' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4">{{ now()->subDays($i)->format('d M Y') }}</td>
                                <td class="py-2 px-4 text-right">
                                    <button
                                        class="cursor-pointer px-3 py-1 rounded-md border border-gray-300 hover:bg-gray-50">View</button>
                                    <button
                                        class="cursor-pointer px-3 py-1 rounded-md bg-red-500 text-white hover:bg-red-600">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination Dummy --}}
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <p>Showing 1 to 10 of 50 results</p>
            <div class="flex gap-1">
                <button class="px-3 py-1 rounded-md border hover:bg-gray-50">Prev</button>
                <button class="px-3 py-1 rounded-md bg-green-600 text-white">1</button>
                <button class="px-3 py-1 rounded-md border hover:bg-gray-50">2</button>
                <button class="px-3 py-1 rounded-md border hover:bg-gray-50">3</button>
                <button class="px-3 py-1 rounded-md border hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>
@endsection
