@extends('layouts.app')

@section('title', 'Manage Task')

@section('content')
    @php
        $role = auth()->user()?->role;
        if ($role === 'owner') {
            $root = 'Product Manager';
        } else {
            $root = 'Menu';
        }
    @endphp
    <x-nav-locate :items="[$root, 'Manage Task']" />

    {{-- konten revenue di bawah --}}
    <div class="bg-white rounded-lg shadow p-6">
        ...
    </div>
@endsection
