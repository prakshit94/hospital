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
                    <x-ui.button href="{{ route('roles.create') }}" data-modal-open>Create Role</x-ui.button>
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

            <form method="GET" class="data-toolbar lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.7fr)_auto]" data-async-search data-target="#roles-results" action="{{ route('roles.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search roles or slugs..." class="ui-input">
                <select name="per_page" class="ui-select">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 5) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>
                <x-ui.button class="justify-center">Apply</x-ui.button>
            </form>

            <div id="roles-results">
                @include('roles.partials.results')
            </div>
        </section>
    </div>
@endsection
