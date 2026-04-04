@extends('layouts.app')

@php
    $pageTitle = 'Dashboard';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="grid gap-8 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.8fr)] xl:items-center">
                <div>
                    <span class="hero-kicker">Dashboard</span>
                    <h1 class="hero-title">Security operations overview</h1>
                    <p class="hero-copy">Monitor access, recent activity, and account changes from one place.</p>

                    <div class="hero-actions">
                        @if(auth()->user()?->hasPermission('users.create'))
                            <x-ui.button variant="secondary" href="{{ route('users.create') }}" data-modal-open>Create User</x-ui.button>
                        @endif
                        @if(auth()->user()?->hasPermission('roles.create'))
                            <x-ui.button href="{{ route('roles.create') }}" data-modal-open>Create Role</x-ui.button>
                        @endif
                        <x-ui.button variant="ghost" href="{{ route('reports.index') }}">Open Reports</x-ui.button>
                    </div>

                    <div class="hero-inline-metrics">
                        @foreach($stats as $stat)
                            <div class="hero-inline-metric">
                                <div class="hero-inline-metric-label">{{ $stat['label'] }}</div>
                                <div class="hero-inline-metric-value">{{ $stat['value'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                    <div class="metric-card">
                        <div class="metric-label">Recent Activity</div>
                        <div class="mt-3 space-y-3">
                            @forelse($activities->take(2) as $activity)
                                <div class="list-card">
                                    <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                                    <div class="mt-1 text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                                </div>
                            @empty
                                <div class="empty-state">No recent activity yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="metric-grid">
            @foreach($stats as $stat)
                <div class="metric-card">
                    <div class="metric-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19h16"/>
                            <path d="M4 15 8 9l4 3 6-8"/>
                        </svg>
                    </div>
                    <div class="metric-label">{{ $stat['label'] }}</div>
                    <div class="metric-value">{{ $stat['value'] }}</div>
                    <div class="metric-meta">{{ $stat['change'] }}</div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_minmax(300px,0.8fr)]">
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Activity</div>
                        <h2 class="section-title">Recent user activity</h2>
                        <p class="section-copy">Latest sign-ins and administrative changes.</p>
                    </div>
                    <x-ui.button variant="ghost" href="{{ route('activity-logs.index') }}">Open Log</x-ui.button>
                </div>

                <div class="space-y-3">
                    @forelse($activities as $activity)
                        <div class="list-card">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span class="ui-chip">{{ $activity->action }}</span>
                                        <span class="ui-chip-muted">{{ $activity->causer?->name ?? 'System' }}</span>
                                    </div>
                                </div>
                                <div class="text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Activity will appear here once users start signing in or changing records.</div>
                    @endforelse
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Users</div>
                        <h2 class="section-title">Newest team members</h2>
                        <p class="section-copy">Recently added users and their current role.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($users as $user)
                        <div class="list-card">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-foreground">{{ $user->name }}</div>
                                    <div class="mt-1 text-xs text-muted-foreground">{{ $user->email }}</div>
                                </div>
                                <span class="ui-chip">{{ $user->primaryRole()?->name ?? 'No role' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">No users found yet.</div>
                    @endforelse
                </div>
            </x-ui.card>
        </section>
    </div>
@endsection
