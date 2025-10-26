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
        modalOrders: [],
        showConfirmDone: false,
        selectedOrderStage: null,
        isSubmitting: false,
        async markAsDone(orderStageId, invoiceNo, productName) {
            this.selectedOrderStage = { id: orderStageId, invoice: invoiceNo, product: productName };
            this.showConfirmDone = true;
        },
        async confirmDone() {
            if (!this.selectedOrderStage || this.isSubmitting) return;
    
            this.isSubmitting = true;
    
            try {
                const response = await fetch('{{ route('karyawan.task.mark-done') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        order_stage_id: this.selectedOrderStage.id
                    })
                });
    
                const data = await response.json();
    
                if (data.success) {
                    // Show success toast
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: data.message, type: 'success' }
                    }));
    
                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    throw new Error(data.message || 'Failed to mark as done');
                }
            } catch (error) {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { message: error.message || 'An error occurred', type: 'error' }
                }));
                this.isSubmitting = false;
            }
        }
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
                                            {{-- Done Button (only if not done yet) --}}
                                            <div class="flex-shrink-0">
                                                @if ($orderStage->status !== 'done')
                                                    <button type="button"
                                                        @click="markAsDone({{ $orderStage->id }}, '{{ $orderStage->order->invoice->invoice_no ?? 'N/A' }}', '{{ $orderStage->order->productCategory->product_name ?? 'N/A' }}')"
                                                        class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-[10px] font-semibold transition-colors cursor-pointer"
                                                        title="Mark as Done">
                                                        Done
                                                    </button>
                                                @else
                                                    <svg class="w-4 h-4 text-green-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
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

        {{-- Confirmation Modal - Mark as Done --}}
        <div x-show="showConfirmDone" x-cloak x-transition.opacity
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/50 backdrop-blur-sm">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click.away="showConfirmDone = false; isSubmitting = false"
                    class="bg-white rounded-xl shadow-lg w-full max-w-md">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-center p-6 border-b border-gray-200">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Mark Task as Done?</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            You are about to mark this task as completed:
                        </p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
                            <p class="text-sm font-medium text-gray-900">
                                <span x-text="selectedOrderStage?.invoice"></span>
                                <span x-text="selectedOrderStage?.product"></span>
                            </p>
                        </div>
                        <p class="text-xs text-gray-500">
                            This action will update the stage status in PM Manage Task.
                        </p>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex gap-3 p-6 border-t border-gray-200">
                        <button type="button" @click="showConfirmDone = false; isSubmitting = false"
                            :disabled="isSubmitting"
                            class="flex-1 px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium cursor-pointer transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Cancel
                        </button>
                        <button type="button" @click="confirmDone()" :disabled="isSubmitting"
                            class="flex-1 px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white text-sm font-medium cursor-pointer transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Yes, Mark as Done</span>
                            <span x-show="isSubmitting">Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
