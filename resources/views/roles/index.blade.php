@extends('layouts.app')

@php
    $pageTitle = 'Roles';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">Roles</span>
                    <h1 class="hero-title">Roles</h1>
                    <p class="hero-copy">Create and maintain reusable access profiles.</p>
                </div>
                @if(auth()->user()?->hasPermission('roles.create'))
                    <x-ui.button href="{{ route('roles.create') }}" data-modal-open>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>
                        Create Role
                    </x-ui.button>
                @endif
            </div>
        </section>

        <section class="data-shell">
            <div class="section-header">
                <div>
                    <div class="section-kicker">Filters</div>
                    <h2 class="section-title">Inspect role coverage</h2>
                    <p class="section-copy">Search roles and review coverage.</p>
                </div>
            </div>

            <form method="GET" class="data-toolbar lg:grid-cols-[minmax(0,1.5fr)_minmax(200px,0.5fr)]" data-async-search data-target="#roles-results" action="{{ route('roles.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search roles or slugs..." class="ui-input">
                <select name="per_page" class="ui-select">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 5) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>
            </form>

            <div id="roles-results">
                @include('roles.partials.results')
            </div>
        </section>
    </div>
@endsection
