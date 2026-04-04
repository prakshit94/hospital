@extends('layouts.app')

@php
    $pageTitle = 'Activity Logs';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">Activity</span>
                    <h1 class="hero-title">Activity Logs</h1>
                    <p class="hero-copy">Review sign-ins, API usage, and CRUD events.</p>
                </div>
            </div>
        </section>

        <section class="data-shell">
            <div class="section-header">
                <div>
                    <div class="section-kicker">Filters</div>
                    <h2 class="section-title">Explore the audit history</h2>
                    <p class="section-copy">Search activity by action or description.</p>
                </div>
            </div>

            <form method="GET" class="data-toolbar lg:grid-cols-[minmax(0,1.3fr)_minmax(0,0.7fr)_auto]" data-async-search data-target="#activities-results" action="{{ route('activity-logs.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activity, actor, or action..." class="ui-input">
                <select name="per_page" class="ui-select">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 5) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>
                <x-ui.button class="justify-center">Apply</x-ui.button>
            </form>

            <div id="activities-results">
                @include('activity-logs.partials.results')
            </div>
        </section>
    </div>
@endsection
