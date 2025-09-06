@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    @php
        $role = auth()->user()?->role;
        if ($role === 'owner') {
            $root = 'Admin';
        } else {
            $root = 'Menu';
        }
    @endphp
    <x-nav-locate :items="[$root, 'Customers']" />

    {{-- konten revenue di bawah --}}
    <div class="bg-white rounded-lg shadow p-6">
        ...
    </div>
@endsection
