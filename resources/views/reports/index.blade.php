@extends('layouts.app')

@php
    $pageTitle = 'Reports';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.7fr)] xl:items-end">
                <div>
                    <span class="hero-kicker">Reports</span>
                    <h1 class="hero-title">Reports and exports</h1>
                    <p class="hero-copy">Reporting for users, roles, permissions, and audit activity.</p>
                    <div class="hero-actions">
                        @if(auth()->user()?->hasPermission('reports.export'))
                            <x-ui.button variant="secondary" href="{{ route('reports.export', 'users') }}">Export Users</x-ui.button>
                            <x-ui.button href="{{ route('reports.export', 'activities') }}">Export Activity</x-ui.button>
                        @endif
                    </div>
                </div>
                <div class="hero-inline-metrics sm:grid-cols-2 xl:grid-cols-2">
                    <div class="hero-inline-metric">
                        <div class="hero-inline-metric-label">Active Users</div>
                        <div class="hero-inline-metric-value">{{ $summary['active_users'] }}</div>
                    </div>
                    <div class="hero-inline-metric">
                        <div class="hero-inline-metric-label">Audit Entries</div>
                        <div class="hero-inline-metric-value">{{ $summary['activities'] }}</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="metric-grid xl:grid-cols-5">
            <div class="metric-card"><div class="metric-label">Users</div><div class="metric-value">{{ $summary['users'] }}</div><div class="metric-meta">Managed accounts</div></div>
            <div class="metric-card"><div class="metric-label">Active Users</div><div class="metric-value">{{ $summary['active_users'] }}</div><div class="metric-meta">Current access-ready users</div></div>
            <div class="metric-card"><div class="metric-label">Roles</div><div class="metric-value">{{ $summary['roles'] }}</div><div class="metric-meta">Reusable access profiles</div></div>
            <div class="metric-card"><div class="metric-label">Permissions</div><div class="metric-value">{{ $summary['permissions'] }}</div><div class="metric-meta">Granular abilities</div></div>
            <div class="metric-card"><div class="metric-label">Activities</div><div class="metric-value">{{ $summary['activities'] }}</div><div class="metric-meta">Captured audit events</div></div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Users</div>
                        <h2 class="section-title">Latest accounts</h2>
                        <p class="section-copy">Recently created accounts.</p>
                    </div>
                    @if(auth()->user()?->hasPermission('reports.export'))
                        <x-ui.button variant="ghost" href="{{ route('reports.export', 'users') }}">CSV</x-ui.button>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($recentUsers as $user)
                        <div class="list-card">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-foreground">{{ $user->name }}</div>
                                    <div class="mt-1 text-xs text-muted-foreground">{{ $user->email }}</div>
                                </div>
                                <span class="ui-chip">{{ $user->roles->pluck('name')->implode(', ') ?: 'No role' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Roles</div>
                        <h2 class="section-title">Coverage by role</h2>
                        <p class="section-copy">Users and permission counts by role.</p>
                    </div>
                    @if(auth()->user()?->hasPermission('reports.export'))
                        <x-ui.button variant="ghost" href="{{ route('reports.export', 'roles') }}">CSV</x-ui.button>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($roles as $role)
                        <div class="list-card">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-foreground">{{ $role->name }}</div>
                                    <div class="mt-1 text-xs text-muted-foreground">{{ $role->slug }}</div>
                                </div>
                                <div class="text-right text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">
                                    <div>{{ $role->users_count }} users</div>
                                    <div class="mt-1">{{ $role->permissions_count }} permissions</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Groups</div>
                        <h2 class="section-title">Grouped access areas</h2>
                        <p class="section-copy">Permission groups.</p>
                    </div>
                    @if(auth()->user()?->hasPermission('reports.export'))
                        <x-ui.button variant="ghost" href="{{ route('reports.export', 'permissions') }}">CSV</x-ui.button>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($permissionGroups as $group)
                        <div class="list-card flex items-center justify-between gap-4">
                            <div class="text-sm font-semibold text-foreground">{{ str_replace('_', ' ', $group->group_name) }}</div>
                            <span class="ui-chip-muted">{{ $group->total }} permissions</span>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Activity</div>
                        <h2 class="section-title">Latest audit events</h2>
                        <p class="section-copy">Latest audit events.</p>
                    </div>
                    @if(auth()->user()?->hasPermission('reports.export'))
                        <x-ui.button variant="ghost" href="{{ route('reports.export', 'activities') }}">CSV</x-ui.button>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                        <div class="list-card">
                            <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">
                                <span>{{ $activity->causer?->email ?? 'System' }}</span>
                                <span>{{ $activity->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </section>
    </div>
@endsection
