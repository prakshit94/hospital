@extends('layouts.app')

@php
    $pageTitle = 'Activity Logs';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div>
            <h1 class="font-heading text-3xl font-black tracking-tight">Activity Logs</h1>
            <p class="mt-2 text-sm text-muted-foreground">Audit trail for sign-ins, API token usage, and CRUD operations across the system.</p>
        </div>

        <x-ui.card>
            <form method="GET" class="mb-5 grid gap-3 lg:grid-cols-[1fr_auto_auto]" data-async-search data-target="#activities-results" action="{{ route('activity-logs.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activity..." class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                <select name="per_page" class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 5) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>
                <x-ui.button class="justify-center">Filter</x-ui.button>
            </form>

            <div id="activities-results">
                @include('activity-logs.partials.results')
            </div>
        </x-ui.card>
    </div>
@endsection
