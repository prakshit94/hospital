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
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1 p-1 rounded-2xl border border-border bg-secondary/50">
                        <a href="{{ route('users.index', array_merge(request()->all(), ['status' => 'active', 'page' => 1])) }}" 
                           class="flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 {{ !request('status') || request('status') === 'active' ? 'bg-card text-primary shadow-sm border border-border' : 'text-muted-foreground hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Active
                        </a>
                        <a href="{{ route('users.index', array_merge(request()->all(), ['status' => 'deleted', 'page' => 1])) }}" 
                           class="flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 {{ request('status') === 'deleted' ? 'bg-destructive/10 text-destructive shadow-sm border border-destructive/20' : 'text-muted-foreground hover:text-destructive/80' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            Trash
                        </a>
                    </div>
                    @if(auth()->user()?->hasPermission('users.create'))
                        <x-ui.button href="{{ route('users.create') }}" data-modal-open>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            Create User
                        </x-ui.button>
                    @endif
                </div>
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

            <form method="GET" class="data-toolbar items-center lg:grid-cols-[minmax(0,1.3fr)_repeat(2,minmax(0,0.7fr))_auto]" data-async-search data-target="#users-results" action="{{ route('users.index') }}">
                <input type="hidden" name="status" value="{{ request('status', 'active') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="ui-input">
                
                <select name="role" class="ui-select">
                    <option value="">All roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected((string) request('role') === (string) $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                <select name="per_page" class="ui-select">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 5) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>
                <div class="flex items-center gap-2">
                    @if(request()->anyFilled(['search', 'role']) || request('status') === 'deleted')
                        <a href="{{ route('users.index') }}" class="ui-button-secondary h-11 px-4 rounded-xl flex items-center justify-center">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <div id="users-results">
                @include('users.partials.results')
            </div>
        </section>
    </div>
@endsection
