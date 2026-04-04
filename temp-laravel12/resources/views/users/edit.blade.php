@extends('layouts.app')

@php
    $pageTitle = 'Edit User';
    $submitLabel = 'Update User';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Edit User</h1>
                <p class="mt-2 text-sm text-muted-foreground">Update identity details, status, and assigned roles for {{ $user->email }}.</p>
            </div>
            <x-ui.button variant="ghost" href="{{ route('users.show', $user) }}">View Profile</x-ui.button>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('users._form')
        </form>
    </div>
@endsection
