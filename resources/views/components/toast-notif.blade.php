<div x-data="{
    show: false,
    message: '',
    type: 'success'
}" x-init="@if (session('success_add')) message = '{{ session('success_add') }}'; type = 'success'; show = true;
            setTimeout(() => show = false, 4000);
        @elseif(session('success_edit'))
            message = '{{ session('success_edit') }}'; type = 'success'; show = true;
            setTimeout(() => show = false, 4000);
        @elseif(session('info_edit'))
            message = '{{ session('info_edit') }}'; type = 'info'; show = true;
            setTimeout(() => show = false, 4000);
        @elseif(session('success_add_sales'))
            message = '{{ session('success_add_sales') }}'; type = 'success'; show = true;
            setTimeout(() => show = false, 4000);
        @elseif(session('success_edit_sales'))
            message = '{{ session('success_edit_sales') }}'; type = 'success'; show = true;
            setTimeout(() => show = false, 4000);
        @elseif(session('info_edit_sales'))
            message = '{{ session('info_edit_sales') }}'; type = 'info'; show = true;
            setTimeout(() => show = false, 4000);
        @elseif(session('success'))
            message = '{{ session('success') }}'; type = 'success'; show = true;
            setTimeout(() => show = false, 4000); @endif" class="fixed top-5 right-5 z-[9999]">
    <template x-if="show">
        <div x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform transition ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
            class="flex items-center justify-between w-80 px-4 py-3 rounded-lg shadow-lg"
            :class="type === 'success' ?
                'bg-green-100 border border-green-400 text-green-700' :
                'bg-yellow-100 border border-yellow-400 text-yellow-700'">
            <span x-text="message" class="text-sm"></span>
            <button @click="show = false" class="ml-3 text-gray-500 hover:text-gray-700">✕</button>
        </div>
    </template>
</div>
