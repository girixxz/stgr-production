<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    {{-- Google Fonts: Outfit --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- penting buat sembunyiin elemen x-cloak saat load --}}
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>

    {{-- Tom Js --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

</head>

{{-- tambahkan x-data + listener event toggle --}}

<body x-data="{
    sidebarOpen: window.matchMedia('(min-width: 768px)').matches,
    init() {
        // Listen untuk perubahan ukuran layar
        window.addEventListener('resize', () => {
            const isMdOrLarger = window.matchMedia('(min-width: 768px)').matches;
            // Auto tutup sidebar jika di mobile, auto buka jika di MD+
            this.sidebarOpen = isMdOrLarger;
        });
    }
}" @sidebar-toggle.window="sidebarOpen = !sidebarOpen" class="h-screen flex bg-gray-light">

    {{-- SIDEBAR WRAPPER + OVERLAY --}}
    {{-- Di mobile: sidebar overlay (fixed), di MD+: sidebar push konten (flex) --}}
    <div class="hidden md:block relative z-40 flex-shrink-0 overflow-hidden transition-all duration-300 ease-out"
        x-bind:class="sidebarOpen ? 'w-64' : 'w-0'">
        {{-- container sidebar untuk MD+ --}}
        <div class="fixed inset-y-0 left-0 w-64 transform transition-transform duration-300 ease-out"
            x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            @include('partials.sidebar')
        </div>
    </div>

    {{-- Sidebar Mobile: Overlay di atas konten --}}
    <div class="md:hidden">
        {{-- overlay gelap saat sidebar terbuka (hanya di mobile) --}}
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/40 z-40"
            @click="$dispatch('sidebar-toggle')" aria-hidden="true"></div>

        {{-- container sidebar mobile: overlay --}}
        <div class="fixed inset-y-0 left-0 w-64 transform transition-transform duration-300 ease-out z-50"
            x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            @include('partials.sidebar')
        </div>
    </div>

    {{-- MAIN - konten akan otomatis adjust sesuai lebar sidebar di MD+ --}}
    <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300 ease-out">
        @include('partials.navbar')

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

    {{-- Notif Toast --}}
    <x-toast-notif />

    {{-- Script --}}
    @stack('scripts')

    {{-- Flowbite --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
