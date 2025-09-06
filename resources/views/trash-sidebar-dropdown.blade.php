<div class="flex flex-col h-screen bg-white border-r border-gray-200 w-64">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 border-b border-gray-200">
        <a href="{{ route('owner.dashboard') }}" class="text-2xl font-bold text-green-700">STGR</a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 text-sm text-gray-600">
        {{-- Owner Menu --}}
        <div class="mb-3">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase mb-2">Menu</p>

            <ul class="space-y-1 font-medium">
                <!-- Dashboard -->
                <li>
                    <x-sidebar-menu.main-menu href="{{ route('owner.dashboard') }}" :pattern="'owner.dashboard'">
                        <x-icons.dashboard class="text-current" />
                        <span class="ml-2">Dashboard</span>
                    </x-sidebar-menu.main-menu>
                </li>

                <!-- Revenue -->
                <li>
                    <x-sidebar-menu.main-menu href="{{ Route::has('owner.revenue') ? route('owner.revenue') : '#' }}"
                        :pattern="['owner.revenue.*', 'owner/revenue*']">
                        <x-icons.revenue class="text-current" />
                        <span class="ml-2">Revenue</span>
                    </x-sidebar-menu.main-menu>
                </li>

                <!-- Manage Data -->
                <li x-data="{
                    open: @js(request()->routeIs('owner.manage-products') || request()->is('owner/manage-products') || request()->routeIs('owner.manage-wo') || request()->is('owner/manage-wo') || request()->routeIs('owner.manage-users-sales') || request()->is('owner/manage-users-sales'))
                }">
                    <button type="button" @click="open = !open"
                        class="flex items-center justify-between w-full pl-6 pr-4 py-3 rounded-md hover:bg-gray-100 focus:outline-none cursor-pointer">
                        <span class="flex items-center">
                            @php
                                $mdActive =
                                    request()->routeIs('owner.manage-products') ||
                                    request()->is('owner/manage-products') ||
                                    request()->routeIs('owner.manage-wo') ||
                                    request()->is('owner/manage-wo') ||
                                    request()->routeIs('owner.users-sales.*') ||
                                    request()->is('owner/users-sales*');
                            @endphp
                            <x-icons.manage-data class="{{ $mdActive ? 'text-green-700' : 'text-gray-500' }}" />
                            <span class="ml-2 {{ $mdActive ? 'text-green-700' : '' }}">Manage Data</span>
                        </span>
                        <x-icons.right-arrow class="text-gray-500 transition-transform duration-200"
                            x-bind:class="open ? 'rotate-90' : ''" />
                    </button>

                    <ul class="mt-1 space-y-2 font-normal" x-show="open" x-transition x-cloak>
                        <li>
                            <x-sidebar-menu.sub-menu href="{{ route('owner.manage-products') }}" :pattern="['owner.manage-products', 'owner/manage-products*']">
                                Products
                            </x-sidebar-menu.sub-menu>
                        </li>

                        {{-- Nanti sesuaikan setelah route-nya ada --}}
                        <li>
                            <x-sidebar-menu.sub-menu
                                href="{{ Route::has('owner.manage-wo') ? route('owner.manage-wo') : '#' }}"
                                :pattern="['owner.manage-wo', 'owner/manage-wo']">
                                Work Order
                            </x-sidebar-menu.sub-menu>
                        </li>
                        <li>
                            <x-sidebar-menu.sub-menu
                                href="{{ Route::has('owner.manage-users-sales') ? route('owner.manage-users-sales') : '#' }}"
                                :pattern="['owner.manage-users-sales', 'owner/manage-users-sales']">
                                Users & Sales
                            </x-sidebar-menu.sub-menu>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

        {{-- Admin Menu --}}
        <div class="mb-3">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase mb-2">ADMIN</p>

            <ul class="space-y-1 font-medium">
                <!-- Orders -->
                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.orders class="text-gray-500" />
                        <span class="ml-2">Orders</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.work-orders class="text-gray-500" />
                        <span class="ml-2">Work Orders</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.payment-history class="text-gray-500" />
                        <span class="ml-2">Payment History</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.customers class="text-gray-500" />
                        <span class="ml-2">Customers</span>
                    </a>
                </li>

            </ul>
        </div>

        {{-- Admin Menu --}}
        <div x-data="{ openAdmin: false }" class="mb-3">
            <button type="button" @click="openAdmin = !openAdmin"
                class="flex items-center justify-between w-full px-4 py-3 rounded-md
                       text-xs font-semibold text-gray-400 uppercase cursor-pointer
                       hover:text-gray-600 hover:bg-gray-100 focus:outline-none mb-2">
                <span>Admin</span>
                <x-icons.right-arrow class="w-4 h-4 transition-transform duration-200"
                    x-bind:class="openAdmin ? 'rotate-90' : ''" />
            </button>

            <ul x-cloak x-show="openAdmin" x-transition class="mt-1 space-y-1 font-medium">

                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.orders class="text-gray-500" />
                        <span class="ml-2">Orders</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.work-orders class="text-gray-500" />
                        <span class="ml-2">Work Orders</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.payment-history class="text-gray-500" />
                        <span class="ml-2">Payment History</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.customers class="text-gray-500" />
                        <span class="ml-2">Customers</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- PM Menu --}}
        <div x-data="{ openPM: false }" class="mb-3">
            <button type="button" @click="openPM = !openPM"
                class="flex items-center justify-between w-full px-4 py-3 rounded-md
                       text-xs font-semibold text-gray-400 uppercase cursor-pointer
                       hover:text-gray-600 hover:bg-gray-100 focus:outline-none mb-2">
                <span>Product Manager</span>
                <x-icons.right-arrow class="w-4 h-4 transition-transform duration-200"
                    x-bind:class="openPM ? 'rotate-90' : ''" />
            </button>

            <ul x-cloak x-show="openPM" x-transition class="mt-1 space-y-1 font-medium">

                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.manage-task class="text-gray-500" />
                        <span class="ml-2">Manage Task</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Karyawan Menu --}}
        <div x-data="{ openKaryawan: false }" class="mb-6">
            <button type="button" @click="openKaryawan = !openKaryawan"
                class="flex items-center justify-between w-full px-4 py-3 rounded-md
                       text-xs font-semibold text-gray-400 uppercase cursor-pointer
                       hover:text-gray-600 hover:bg-gray-100 focus:outline-none mb-2">
                <span>KARYAWAN</span>
                <x-icons.right-arrow class="w-4 h-4 transition-transform duration-200"
                    x-bind:class="openKaryawan ? 'rotate-90' : ''" />
            </button>

            <ul x-cloak x-show="openKaryawan" x-transition class="mt-1 space-y-1 font-medium">

                <li>
                    <a href="#" class="flex items-center px-6 py-3 rounded-md hover:bg-gray-100">
                        <x-icons.task class="text-gray-500" />
                        <span class="ml-2">Task</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout -->
        <div class="px-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center px-6 py-3 rounded-md
                           bg-red-400 text-white hover:bg-red-500 cursor-pointer">
                    <x-icons.logout class="text-white" />
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</div>
