@extends('layouts.app')

@section('title', 'Task')

@section('content')
    @php
        $role = auth()->user()?->role;
        if ($role === 'owner') {
            $root = 'Karyawan';
        } else {
            $root = 'Menu';
        }
    @endphp
    <x-nav-locate :items="[$root, 'Task']" />

    {{-- Root Alpine State --}}
    <div x-data="{
        showModal: false,
        modalStage: null,
        modalOrders: []
    }">

        {{-- Page Title --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Today's Task</h1>
            <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::today()->format('l, d F Y') }}</p>
        </div>

        {{-- Production Stages Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($stagesWithOrders as $stageData)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    {{-- Card Header --}}
                    <div class="bg-gradient-to-r from-primary to-primary-dark p-4">
                        <h3 class="text-white font-semibold text-lg">{{ $stageData['stage']->stage_name }}</h3>
                        <p class="text-white/80 text-xs mt-1">
                            {{ $stageData['total_count'] }} {{ Str::plural('task', $stageData['total_count']) }}
                        </p>
                    </div>

                    {{-- Card Body - Order Bubbles --}}
                    <div class="p-4 min-h-[200px]">
                        @if ($stageData['total_count'] > 0)
                            <div class="space-y-2">
                                @foreach ($stageData['order_stages']->take(5) as $orderStage)
                                    @php
                                        $isHighPriority = strtolower($orderStage->order->priority ?? '') === 'high';
                                        $bubbleClass = $isHighPriority
                                            ? 'bg-yellow-100 border-yellow-300 text-yellow-800'
                                            : 'bg-gray-100 border-gray-300 text-gray-700';
                                    @endphp
                                    <div class="px-3 py-2 rounded-lg border {{ $bubbleClass }} text-xs font-medium">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="truncate">
                                                    {{ $orderStage->order->invoice->invoice_no ?? 'N/A' }}
                                                    {{ $orderStage->order->productCategory->product_name ?? 'N/A' }}
                                                    @if ($isHighPriority)
                                                        <span class="font-bold text-red-600">(HIGH)</span>
                                                    @endif
                                                </p>
                                                <p class="text-[10px] mt-1 opacity-75">
                                                    {{ $orderStage->order->customer->customer_name ?? 'N/A' }}
                                                </p>
                                            </div>
                                            {{-- Status Icon --}}
                                            <div class="flex-shrink-0">
                                                @if ($orderStage->status === 'done')
                                                    <svg class="w-4 h-4 text-green-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @elseif($orderStage->status === 'in_progress')
                                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-8">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-400 text-sm">No tasks today</p>
                            </div>
                        @endif
                    </div>

                    {{-- Card Footer - Show More --}}
                    @if ($stageData['remaining_count'] > 0)
                        <div class="border-t border-gray-200 p-3 bg-gray-50">
                            <button type="button"
                                @click="showModal = true; 
                                        modalStage = '{{ $stageData['stage']->stage_name }}'; 
                                        modalOrders = {{ $stageData['order_stages']->map(function ($os) {
                                                $isHigh = strtolower($os->order->priority ?? '') === 'high';
                                                return [
                                                    'invoice' => $os->order->invoice->invoice_no ?? 'N/A',
                                                    'product' => $os->order->productCategory->product_name ?? 'N/A',
                                                    'customer' => $os->order->customer->customer_name ?? 'N/A',
                                                    'priority' => $isHigh ? 'high' : 'normal',
                                                    'status' => $os->status,
                                                    'deadline' => $os->deadline ? $os->deadline->format('d M Y H:i') : 'N/A',
                                                ];
                                            })->toJson() }}"
                                class="w-full text-center text-sm font-medium text-primary hover:text-primary-dark transition-colors cursor-pointer">
                                {{ $stageData['remaining_count'] }}+ More
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Modal - Show All Orders --}}
        <div x-show="showModal" x-cloak x-transition.opacity
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/50 backdrop-blur-sm">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="showModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-2xl">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900" x-text="modalStage"></h3>
                            <p class="text-sm text-gray-500 mt-1">All tasks for today</p>
                        </div>
                        <button @click="showModal = false"
                            class="text-gray-400 hover:text-gray-600 cursor-pointer transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-5 max-h-[60vh] overflow-y-auto">
                        <div class="space-y-3">
                            <template x-for="(order, index) in modalOrders" :key="index">
                                <div class="px-4 py-3 rounded-lg border"
                                    :class="order.priority === 'high' ? 'bg-yellow-50 border-yellow-300' :
                                        'bg-gray-50 border-gray-200'">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 text-sm">
                                                <span x-text="order.invoice"></span>
                                                <span x-text="order.product"></span>
                                                <span x-show="order.priority === 'high'"
                                                    class="font-bold text-red-600 ml-1">(HIGH)</span>
                                            </p>
                                            <p class="text-xs text-gray-600 mt-1" x-text="order.customer"></p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Deadline: <span x-text="order.deadline"></span>
                                            </p>
                                        </div>
                                        {{-- Status Badge --}}
                                        <div class="flex-shrink-0">
                                            <span x-show="order.status === 'done'"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Done
                                            </span>
                                            <span x-show="order.status === 'in_progress'"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                In Progress
                                            </span>
                                            <span x-show="order.status === 'pending'"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Pending
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex justify-end gap-3 p-5 border-t border-gray-200">
                        <button @click="showModal = false"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium cursor-pointer transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
