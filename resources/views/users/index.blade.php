@extends('layouts.app')

@php
    $pageTitle = 'Users';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">Users</span>
                    <h1 class="hero-title">Users</h1>
                    <p class="hero-copy">Manage sign-in access, account status, and role assignments.</p>
                </div>
                @if(auth()->user()?->hasPermission('users.create'))
                    <x-ui.button href="{{ route('users.create') }}" data-modal-open>Create User</x-ui.button>
                @endif
            </div>
        </section>

        <section class="data-shell">
            <div class="section-header">
                <div>
                    <div class="section-kicker">Filters</div>
                    <h2 class="section-title">Browse and refine user records</h2>
                    <p class="section-copy">Search by name, status, or role.</p>
                </div>
            </div>

            <form method="GET" class="data-toolbar lg:grid-cols-[minmax(0,1.3fr)_repeat(3,minmax(0,0.7fr))]" data-async-search data-target="#users-results" action="{{ route('users.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="ui-input">
                <select name="status" class="ui-select">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
                <select name="role" class="ui-select">
                    <option value="">All roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected((string) request('role') === (string) $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                <div class="flex gap-3">
                    <select name="per_page" class="ui-select">
                        @foreach([5, 10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" @selected((int) request('per_page', 5) === $size)>{{ $size }} per page</option>
                        @endforeach
                    </select>
                    <x-ui.button class="justify-center">Apply</x-ui.button>
                </div>
            </form>

            <div id="users-results">
                @include('users.partials.results')
            </div>
        </section>
    </div>
@endsection
