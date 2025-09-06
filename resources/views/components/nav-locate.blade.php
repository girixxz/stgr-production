@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center text-sm">
        @foreach ($items as $raw)
            @php
                // Boleh kirim array ['label' => '...'] atau string langsung
                $label = is_array($raw) ? $raw['label'] ?? '' : $raw;
            @endphp

            @if (!$loop->first)
                <li class="mx-2 text-gray-400 select-none">/</li>
            @endif

            <li class="inline-flex items-center">
                <span
                    class="px-3 py-1 rounded-md
                 {{ $loop->last ? 'bg-green-200 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ $label }}
                </span>
            </li>
        @endforeach
    </ol>
</nav>
