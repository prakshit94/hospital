@extends('layouts.app')

@php
    $pageTitle = 'Reports';
@endphp

@section('content')
    <div class="space-y-8 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Reports & Exports</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">Operational reporting for users, roles, permissions, and audit activity, with direct CSV export actions.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(auth()->user()?->hasPermission('reports.export'))
                    <x-ui.button variant="secondary" href="{{ route('reports.export', 'users') }}">Export Users</x-ui.button>
                    <x-ui.button href="{{ route('reports.export', 'activities') }}">Export Activity</x-ui.button>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <x-ui.card><div class="text-xs font-black uppercase tracking-[0.22em] text-muted-foreground">Users</div><div class="mt-3 font-heading text-3xl font-black">{{ $summary['users'] }}</div></x-ui.card>
            <x-ui.card><div class="text-xs font-black uppercase tracking-[0.22em] text-muted-foreground">Active Users</div><div class="mt-3 font-heading text-3xl font-black">{{ $summary['active_users'] }}</div></x-ui.card>
            <x-ui.card><div class="text-xs font-black uppercase tracking-[0.22em] text-muted-foreground">Roles</div><div class="mt-3 font-heading text-3xl font-black">{{ $summary['roles'] }}</div></x-ui.card>
            <x-ui.card><div class="text-xs font-black uppercase tracking-[0.22em] text-muted-foreground">Permissions</div><div class="mt-3 font-heading text-3xl font-black">{{ $summary['permissions'] }}</div></x-ui.card>
            <x-ui.card><div class="text-xs font-black uppercase tracking-[0.22em] text-muted-foreground">Activities</div><div class="mt-3 font-heading text-3xl font-black">{{ $summary['activities'] }}</div></x-ui.card>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-ui.card>
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="font-heading text-xl font-bold">Recent Users</h2>
                        <p class="text-sm text-muted-foreground">Recently created accounts and their role coverage.</p>
                    </div>
                    @if(auth()->user()?->hasPermission('reports.export'))
                        <x-ui.button variant="ghost" href="{{ route('reports.export', 'users') }}">CSV</x-ui.button>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($recentUsers as $user)
                        <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-foreground">{{ $user->name }}</div>
                                    <div class="mt-1 text-xs text-muted-foreground">{{ $user->email }}</div>
                                </div>
                                <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $user->roles->pluck('name')->implode(', ') ?: 'No role' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="font-heading text-xl font-bold">Role Matrix</h2>
                        <p class="text-sm text-muted-foreground">Users and permission counts by role.</p>
                    </div>
                    @if(auth()->user()?->hasPermission('reports.export'))
                        <x-ui.button variant="ghost" href="{{ route('reports.export', 'roles') }}">CSV</x-ui.button>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($roles as $role)
                        <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-foreground">{{ $role->name }}</div>
                                    <div class="mt-1 text-xs text-muted-foreground">{{ $role->slug }}</div>
                                </div>
                                <div class="text-right text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">
                                    <div>{{ $role->users_count }} users</div>
                                    <div class="mt-1">{{ $role->permissions_count }} permissions</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-ui.card>
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="font-heading text-xl font-bold">Permission Groups</h2>
                        <p class="text-sm text-muted-foreground">Grouped permissions for easier access review.</p>
                    </div>
                    @if(auth()->user()?->hasPermission('reports.export'))
                        <x-ui.button variant="ghost" href="{{ route('reports.export', 'permissions') }}">CSV</x-ui.button>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($permissionGroups as $group)
                        <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-semibold text-foreground">{{ str_replace('_', ' ', $group->group_name) }}</div>
                                <div class="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">{{ $group->total }} permissions</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="font-heading text-xl font-bold">Recent Activity</h2>
                        <p class="text-sm text-muted-foreground">Latest events captured in the audit log.</p>
                    </div>
                    @if(auth()->user()?->hasPermission('reports.export'))
                        <x-ui.button variant="ghost" href="{{ route('reports.export', 'activities') }}">CSV</x-ui.button>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                        <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                            <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                            <div class="mt-1 text-xs text-muted-foreground">{{ $activity->causer?->email ?? 'System' }} · {{ $activity->created_at?->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
