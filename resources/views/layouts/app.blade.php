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

<body x-data="{ sidebarOpen: false }" @sidebar-toggle.window="sidebarOpen = !sidebarOpen" class="h-screen flex bg-gray-100">

    {{-- OFF-CANVAS WRAPPER + OVERLAY (mobile) --}}
    <div class="relative z-40">
        {{-- overlay gelap saat sidebar terbuka --}}
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/40 lg:hidden"
            @click="$dispatch('sidebar-toggle')" aria-hidden="true"></div>

        {{-- container sidebar: slide-in/out di mobile, normal di desktop --}}
        <div class="fixed inset-y-0 left-0 w-64 transform transition-transform duration-300 ease-out
                    lg:static lg:translate-x-0"
            x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            @include('partials.sidebar')
        </div>
    </div>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.navbar')

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

    <x-toast-notif />

</body>

</html>
