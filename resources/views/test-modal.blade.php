@extends('layouts.app')
@section('title', 'Test Modal')
@section('content')

    <div x-data="{ openModal: '' }" class="p-6">
        <h2 class="text-xl font-bold mb-4">Test Modal Alpine</h2>

        <!-- Debug -->
        <p class="mb-4">openModal sekarang: <span x-text="openModal" class="font-mono text-green-600"></span></p>

        <!-- Tombol -->
        <button type="button" @click="openModal = 'addCategory'"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            + Add Category
        </button>

        <!-- Modal -->
        <div x-show="openModal === 'addCategory'" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg w-80">
                <h3 class="text-lg font-semibold mb-4">Modal Test</h3>
                <p>Halo, ini modal berhasil muncul 🚀</p>
                <div class="mt-4 flex justify-end">
                    <button type="button" @click="openModal = ''"
                        class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
