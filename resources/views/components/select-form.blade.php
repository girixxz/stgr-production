@props(['name', 'placeholder' => '-- Select --', 'options' => [], 'display' => 'name', 'old' => null])

<div x-data="searchSelect(@js($options), '{{ $old }}', '{{ $name }}')" x-init="init()" class="relative w-full">
    {{-- Trigger --}}
    <button type="button" @click="open = !open"
        class="w-full flex justify-between items-center rounded-md border px-3 py-2 text-sm text-gray-700 bg-white
               focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
               {{ $errors->addOrder->has($name) ? 'border-red-500' : 'border-gray-300' }}">
        <span x-text="selected ? selected['{{ $display }}'] : '{{ $placeholder }}'"></span>
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Hidden input --}}
    <input type="hidden" name="{{ $name }}" x-model="selectedId">

    {{-- Dropdown --}}
    <div x-show="open" @click.away="open = false"
        class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg">
        <div class="relative px-4 py-2">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <x-icons.search />
            </div>
            <input type="text" placeholder="Search..." x-model="search"
                class="block w-full h-10 pl-10 pr-3 text-gray-600 text-sm border border-gray-300 rounded-md
                       focus:outline-none focus:ring-1 focus:ring-green-400" />
        </div>

        <ul class="max-h-60 overflow-y-auto">
            <template
                x-for="option in options.filter(o => o['{{ $display }}'].toLowerCase().includes(search.toLowerCase()))"
                :key="option.id">
                <li @click="select(option)" class="px-5 py-2 cursor-pointer text-sm hover:bg-green-50"
                    :class="{ 'bg-green-100 font-medium': selected && selected.id === option.id }">
                    <span x-text="option['{{ $display }}']"></span>
                </li>
            </template>
        </ul>
    </div>

    {{-- Error message dari Laravel --}}
    @error($name, 'addOrder')
        <span class="absolute left-0 -bottom-5 text-[12px] text-red-600">
            {{ $message }}
        </span>
    @enderror
</div>
