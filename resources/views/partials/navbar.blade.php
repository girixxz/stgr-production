<header class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Left: Hamburger -->
        <div class="flex items-center space-x-4">
            <button @click="$dispatch('sidebar-toggle')"
                class="text-gray-600 hover:text-gray-800 focus:outline-none p-2 rounded-md hover:bg-gray-100 md:inline-flex"
                aria-label="Toggle sidebar" title="Toggle sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Center: Nav (Highlight & Calendar) -->
        @php
            $navClasses = function (string $pattern) {
                $base = 'px-3 py-2 rounded-md';
                return request()->routeIs($pattern)
                    ? $base . ' bg-primary-light ring-1 ring-inset ring-primary-light text-font-base font-semibold'
                    : $base . ' text-font-base hover:bg-gray-light';
            };
        @endphp

        <nav class="flex-1 flex items-center justify-center">
            <ul class="flex items-center text-sm md:text-[14px] gap-1">
                <li>
                    <a href="{{ route('highlights') }}" class="{{ $navClasses('highlights') }}">
                        Highlights
                    </a>
                </li>
                <li>
                    <a href="{{ route('calendar') }}" class="{{ $navClasses('calendar') }}">
                        Calendar
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Right: User -->
        @php
            $profile_name = auth()->user()?->fullname;

            // Ganti 'photo_url' dengan nama kolom avatar di tabel users milikmu
            $raw = auth()->user()?->img_url;

            // Jika path lokal, konversi ke URL publik; jika sudah http(s), pakai apa adanya
            $avatarUrl = !empty($raw)
                ? (\Illuminate\Support\Str::startsWith($raw, ['http://', 'https://'])
                    ? $raw
                    : \Illuminate\Support\Facades\Storage::url($raw))
                : 'https://i.pravatar.cc/40?u=' . urlencode(auth()->user()->id ?? auth()->user()->username);
        @endphp
        <div class="flex items-center space-x-4">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @keydown.escape.window="open = false"
                    class="flex items-center rounded-md hover:bg-gray-100 px-3 py-2 focus:outline-none cursor-pointer"
                    x-bind:aria-expanded="open" aria-haspopup="menu" aria-controls="userDropdown">
                    <img class="w-8 h-8 rounded-full" src="{{ $avatarUrl }}" alt="User avatar" />
                    <span class="ml-2 text-gray-700 font-medium hidden sm:block">{{ $profile_name }}</span>
                    <svg class="hidden sm:block w-4 h-4 ml-1 text-gray-500 transition-transform duration-200"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                        stroke-linejoin="round" x-bind:class="open ? 'rotate-180' : ''">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>

                <!-- Dropdown -->
                <div id="userDropdown" x-cloak x-show="open" @click.outside="open = false" x-transition.opacity
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-20"
                    role="menu">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 cursor-pointer"
                            role="menuitem">
                            <x-icons.logout class="" />
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
