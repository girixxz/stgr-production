@extends('layouts.app')

@section('title', 'Task')

@section('content')
    @php
        $role = auth()->user()?->role;
        if ($role === 'owner') {
            $root = 'Karyawan';
        } else {
            $root = 'Menu';
        }
    @endphp
    <x-nav-locate :items="[$root, 'Task']" />

    {{-- konten revenue di bawah --}}
    <div class="bg-white rounded-lg shadow p-6">
        ...
    </div>
@endsection
