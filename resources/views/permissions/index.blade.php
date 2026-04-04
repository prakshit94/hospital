@extends('layouts.app')

@php
    $pageTitle = 'Permissions';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">Permissions</span>
                    <h1 class="hero-title">Permissions</h1>
                    <p class="hero-copy">Manage the individual abilities attached to roles.</p>
                </div>
                @if(auth()->user()?->hasPermission('permissions.create'))
                    <x-ui.button href="{{ route('permissions.create') }}" data-modal-open>Create Permission</x-ui.button>
                @endif
            </div>
        </section>

        <section class="data-shell">
            <div class="section-header">
                <div>
                    <div class="section-kicker">Filters</div>
                    <h2 class="section-title">Browse permission groups</h2>
                    <p class="section-copy">Search by name, slug, or group.</p>
                </div>
            </div>

            <form method="GET" class="data-toolbar lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.7fr)_auto]" data-async-search data-target="#permissions-results" action="{{ route('permissions.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search permissions or groups..." class="ui-input">
                <select name="per_page" class="ui-select">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 5) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>
                <x-ui.button class="justify-center">Apply</x-ui.button>
            </form>

            <div id="permissions-results">
                @include('permissions.partials.results')
            </div>
        </section>
    </div>
@endsection
