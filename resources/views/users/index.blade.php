@extends('layouts.app')

@php
    $pageTitle = 'Users';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Users</h1>
                <p class="mt-2 text-sm text-muted-foreground">Manage sign-in access, account state, and role assignments.</p>
            </div>
            @if(auth()->user()?->hasPermission('users.create'))
                <x-ui.button href="{{ route('users.create') }}">Create User</x-ui.button>
            @endif
        </div>

        <x-ui.card>
            <form method="GET" class="mb-5 grid gap-3 lg:grid-cols-[1fr_auto_auto_auto_auto]" data-async-search data-target="#users-results" action="{{ route('users.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                <select name="status" class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
                <select name="role" class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    <option value="">All roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected((string) request('role') === (string) $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                <select name="per_page" class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 5) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>
                <x-ui.button class="justify-center">Filter</x-ui.button>
            </form>

            <div id="users-results">
                @include('users.partials.results')
            </div>
        </x-ui.card>
    </div>
@endsection
