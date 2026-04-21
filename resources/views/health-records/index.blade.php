@extends('layouts.app')

@php
    $pageTitle = 'Health Records';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">Health Management</span>
                    <h1 class="hero-title">Employee Health Records</h1>
                    <p class="hero-copy">Analyze and manage health data across multiple companies.</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-ui.button href="{{ route('health-records.create') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>
                        New Health Record
                    </x-ui.button>
                </div>
            </div>
        </section>

        <section class="data-shell">
            <div class="section-header">
                <div>
                    <div class="section-kicker">Filters</div>
                    <h2 class="section-title">Browse and refine records</h2>
                    <p class="section-copy">Search by name, company, or employee ID.</p>
                </div>
            </div>

            <form method="GET" class="data-toolbar lg:grid-cols-[minmax(0,1.3fr)_repeat(2,minmax(0,0.7fr))_auto]" data-async-search data-target="#health-records-results" action="{{ route('health-records.index') }}">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, company, or ID..." class="ui-input pl-10">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-muted-foreground/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                </div>
                
                <select name="company" class="ui-select">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company }}" @selected(request('company') == $company)>{{ $company }}</option>
                    @endforeach
                </select>

                <select name="per_page" class="ui-select">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-2">
                    <button type="submit" class="ui-button-primary h-11 px-6 rounded-xl">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'company']))
                        <a href="{{ route('health-records.index') }}" class="ui-button-secondary h-11 px-4 rounded-xl flex items-center justify-center">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <div id="health-records-results">
                @include('health-records.partials.results')
            </div>
        </section>
    </div>
@endsection
