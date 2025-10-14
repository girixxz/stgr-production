@extends('layouts.app')
@section('title', 'Manage Users & Sales')
@section('content')

    <x-nav-locate :items="['Menu', 'Manage Data', 'Users & Sales']" />

    {{-- Root Alpine State --}}
    <div x-data="{
        openModal: '{{ session('openModal') }}',
        editUser: {},
        editSales: {},
        searchUser: '',
        searchSales: ''
    }" class="grid grid-cols-1 gap-6">

        {{-- ===================== USERS ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <h2 class="text-xl font-semibold text-gray-900">Users</h2>

                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto">
                    {{-- Search --}}
                    <div class="w-full md:w-72">
                        <div class="relative">
                            <x-icons.search />
                            <input type="text" x-model="searchUser" placeholder="Search User"
                                class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                        </div>
                    </div>

                    {{-- Add Users --}}
                    <button @click="openModal = 'addUser'"
                        class="cursor-pointer w-32 whitespace-nowrap px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Users
                    </button>
                </div>
            </div>

            {{-- Table Users --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-96 overflow-y-auto">
                    <table class="min-w-[750px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">User</th>
                                <th class="py-2 px-4 text-left">Username</th>
                                <th class="py-2 px-4 text-left">Phone</th>
                                <th class="py-2 px-4 text-left">Role</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-t border-gray-200"
                                    x-show="
                                        '{{ strtolower($user->fullname) }} {{ strtolower($user->username) }} {{ strtolower($user->phone_number) }} {{ strtolower($user->role) }}'
                                        .includes(searchUser.toLowerCase())
                                    ">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $avatarUrl = !empty($user->img_url)
                                                    ? $user->img_url
                                                    : 'https://i.pravatar.cc/40?u=' .
                                                        urlencode($user->id ?? $user->username);
                                            @endphp
                                            <img src="{{ $avatarUrl }}" alt="{{ $user->fullname }}"
                                                class="w-8 h-8 rounded-full object-cover border" />
                                            <span>{{ $user->fullname }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-4">{{ $user->username }}</td>
                                    <td class="py-2 px-4">{{ $user->phone_number }}</td>
                                    <td class="py-2 px-4">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md bg-green-50 text-green-700 ring-1 ring-inset ring-green-200">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Edit --}}
                                            <button @click="editUser = {{ $user->toJson() }}; openModal = 'editUser'"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Edit">
                                                <x-icons.edit class="w-3 h-3 !mr-0" />
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form
                                                action="{{ route('owner.manage-data.users-sales.users.destroy', $user) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                                    title="Delete">
                                                    <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== SALES ===================== --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5">
            {{-- Header --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <h2 class="text-xl font-semibold text-gray-900">Sales</h2>

                <div class="md:ml-auto flex items-center gap-2 w-full md:w-auto min-w-0">
                    {{-- Search --}}
                    <div class="flex-1">
                        <div class="relative">
                            <x-icons.search />
                            <input type="text" x-model="searchSales" placeholder="Search Sales"
                                class="w-full rounded-md border border-gray-300 pl-9 pr-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-300" />
                        </div>
                    </div>

                    {{-- Add Sales --}}
                    <button @click="openModal = 'addSales'"
                        class="cursor-pointer flex-shrink-0 w-32 whitespace-nowrap px-3 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 text-sm text-center">
                        + Add Sales
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="mt-5 overflow-x-auto">
                <div class="max-h-96 overflow-y-auto">
                    <table class="min-w-[450px] w-full text-sm">
                        <thead class="sticky top-0 bg-white text-gray-600 z-10">
                            <tr>
                                <th class="py-2 px-4 text-left">No</th>
                                <th class="py-2 px-4 text-left">Sales Name</th>
                                <th class="py-2 px-4 text-left">Phone</th>
                                <th class="py-2 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr class="border-t border-gray-200"
                                    x-show="
                                        '{{ strtolower($sale->sales_name) }} {{ strtolower($sale->phone_number) }}'
                                        .includes(searchSales.toLowerCase())
                                    ">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $sale->sales_name }}</td>
                                    <td class="py-2 px-4">{{ $sale->phone_number }}</td>

                                    <td class="py-2 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Edit --}}
                                            <button @click="editSales = {{ $sale->toJson() }}; openModal = 'editSales'"
                                                class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100"
                                                title="Edit">
                                                <x-icons.edit class="w-3 h-3 !mr-0" />
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form
                                                action="{{ route('owner.manage-data.users-sales.sales.destroy', $sale) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cursor-pointer inline-flex items-center justify-center px-1 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this sales?')"
                                                    title="Delete">
                                                    <x-icons.trash class="w-2 h-2 !mr-0 text-white" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== MODALS ===================== --}}
        {{-- ========== Add User Modal ========== --}}
        <div x-show="openModal === 'addUser'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add New User</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form action="{{ route('owner.manage-data.users-sales.users.store') }}" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fullname</label>
                        <div class="relative">
                            <input type="text" name="fullname" value="{{ old('fullname') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addUser->has('fullname') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addUser->has('fullname'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('fullname', 'addUser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Username --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="relative">
                            <input type="text" name="username" value="{{ old('username') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addUser->has('username') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addUser->has('username'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('username', 'addUser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone (optional)</label>
                        <input type="text" name="phone_number"
                            class="mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm text-gray-500 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Roles</label>
                        <select name="role"
                            class="mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm
                            text-gray-500 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">
                            <option value="owner">Owner</option>
                            <option value="admin">Admin</option>
                            <option value="pm">Project Manager</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
                    </div>
                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" name="password" value="{{ old('password') }}"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addUser->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->addUser->has('password'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('password', 'addUser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->addUser->has('password_confirmation') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('password_confirmation', 'addUser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Edit User Modal ========== --}}
        <div x-show="openModal === 'editUser'" x-cloak x-init="@if (session('openModal') === 'editUser' && session('editUserId')) editUser = {{ \App\Models\User::find(session('editUserId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>
                <form :action="`/owner/manage-data/users-sales/users/${editUser.id}`" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="fullname" x-model="editUser.fullname"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editUser->has('fullname') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('fullname', 'editUser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Username --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="relative">
                            <input type="text" name="username" x-model="editUser.username"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editUser->has('username') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->editUser->has('username'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('username', 'editUser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone_number" x-model="editUser.phone_number"
                            class="mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm 
                           text-gray-700 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">
                    </div>
                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" x-model="editUser.role"
                            class="mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm 
                           text-gray-700 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">
                            <option value="owner">Owner</option>
                            <option value="admin">Admin</option>
                            <option value="pm">Project Manager</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
                    </div>
                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password (leave blank to keep
                            current)</label>
                        <div class="relative">
                            <input type="password" name="password"
                                class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editUser->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">
                            @if ($errors->editUser->has('password'))
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-red-500 pointer-events-none">

                                    <x-icons.danger />
                                </span>
                            @endif
                        </div>
                        @error('password', 'editUser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm border {{ $errors->editUser->has('password_confirmation') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        @error('password_confirmation', 'editUser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Add Sales Modal ========== --}}
        <div x-show="openModal === 'addSales'" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Sales</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>

                <form action="{{ route('owner.manage-data.users-sales.sales.store') }}" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">Sales Name</label>
                        <input type="text" name="sales_name" value="{{ old('sales_name') }}"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm pr-10 border {{ $errors->addSales->has('sales_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        {{-- Error icon di dalam input --}}
                        @if ($errors->addSales->has('sales_name'))
                            <span class="absolute right-3 top-[42px] -translate-y-1/2 text-red-500 pointer-events-none">

                                <x-icons.danger />
                            </span>
                        @endif

                        @error('sales_name', 'addSales')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone (optional)</label>
                        <input type="text" name="phone_number"
                            class="mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm text-gray-500 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Edit Sales Modal ========== --}}
        <div x-show="openModal === 'editSales'" x-cloak x-init="@if (session('openModal') === 'editSales' && session('editSalesId')) editSales = {{ \App\Models\Sales::find(session('editSalesId'))->toJson() }}; @endif"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/50 backdrop-blur-sm px-4">
            <div @click.away="openModal=null" class="bg-white rounded-xl shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center border-b  border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Sales</h3>
                    <button @click="openModal=null" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>

                <form :action="`/owner/manage-data/users-sales/sales/${editSales.id}`" method="POST"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">Sales Name</label>
                        <input type="text" name="sales_name" value="{{ old('sales_name') }}"
                            x-model="editSales.sales_name"
                            class="mt-1 w-full rounded-md px-4 py-2 text-sm pr-10 border {{ $errors->editSales->has('sales_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : 'border-gray-200 focus:border-green-500 focus:ring-green-200' }} focus:outline-none focus:ring-2 text-gray-700">

                        {{-- Error icon di dalam input --}}
                        @if ($errors->editSales->has('sales_name'))
                            <span class="absolute right-3 top-[42px] -translate-y-1/2 text-red-500 pointer-events-none">

                                <x-icons.danger />
                            </span>
                        @endif

                        @error('sales_name', 'addSales')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone_number" x-model="editSales.phone_number"
                            class="mt-1 w-full rounded-md border border-gray-200 px-4 py-2 text-sm text-gray-500 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200">
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openModal=null"
                            class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
