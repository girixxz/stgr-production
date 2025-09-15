<div x-data="{ show: false, message: '', type: '' }" x-init="@if (session('message')) message = '{{ session('message') }}';
            type = '{{ session('alert-type', 'success') }}';
            show = true;
            setTimeout(() => show = false, 4000); @endif" class="fixed top-5 right-5 z-[9999]">

    <template x-if="show">
        <div x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform transition ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
            class="flex items-center justify-between w-80 px-4 py-3 rounded-lg shadow-lg"
            :class="{
                'bg-green-100 border border-green-400 text-green-700': type === 'success',
                'bg-yellow-100 border border-yellow-400 text-yellow-700': type === 'info',
                'bg-red-100 border border-red-400 text-red-700': type === 'error',
                'bg-blue-100 border border-blue-400 text-blue-700': type === 'warning'
            }">
            <span x-text="message" class="text-sm"></span>
            <button @click="show = false" class="ml-3 text-gray-500 hover:text-gray-700">✕</button>
        </div>
    </template>
</div>
