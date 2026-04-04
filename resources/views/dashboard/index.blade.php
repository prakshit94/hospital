@extends('layouts.app')

@php
    $pageTitle = 'Dashboard';
@endphp

@section('content')
    <div class="space-y-8 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Security Operations Dashboard</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">Monitor account access, RBAC coverage, and the latest system activity from one place.</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()?->hasPermission('users.create'))
                    <x-ui.button variant="secondary" href="{{ route('users.create') }}">Create User</x-ui.button>
                @endif
                @if(auth()->user()?->hasPermission('roles.create'))
                    <x-ui.button href="{{ route('roles.create') }}">Create Role</x-ui.button>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach($stats as $stat)
                <x-ui.card class="relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1 bg-emerald-500"></div>
                    <div class="space-y-3">
                        <div class="text-xs font-black uppercase tracking-[0.22em] text-muted-foreground">{{ $stat['label'] }}</div>
                        <div class="font-heading text-3xl font-black tracking-tight">{{ $stat['value'] }}</div>
                        <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $stat['change'] }}</div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-ui.card class="xl:col-span-2">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="font-heading text-xl font-bold">Recent User Activity</h2>
                        <p class="text-sm text-muted-foreground">Latest logins and administrative changes captured in the audit trail.</p>
                    </div>
                    <x-ui.button variant="ghost" href="{{ route('activity-logs.index') }}">Open log</x-ui.button>
                </div>

                <div class="space-y-3">
                    @forelse($activities as $activity)
                        <div class="rounded-2xl border border-border/60 bg-secondary/35 p-4">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                                    <div class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">
                                        {{ $activity->action }} · {{ $activity->causer?->name ?? 'System' }}
                                    </div>
                                </div>
                                <div class="text-xs font-semibold text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-border/70 p-6 text-sm text-muted-foreground">
                            Activity will appear here once users start signing in or changing records.
                        </div>
                    @endforelse
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="mb-5">
                    <h2 class="font-heading text-xl font-bold">Newest Accounts</h2>
                    <p class="text-sm text-muted-foreground">Quick view of recently added users and their current role.</p>
                </div>
                <div class="space-y-3">
                    @forelse($users as $user)
                        <div class="rounded-2xl border border-border/60 bg-secondary/35 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-foreground">{{ $user->name }}</div>
                                    <div class="mt-1 text-xs text-muted-foreground">{{ $user->email }}</div>
                                </div>
                                <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">
                                    {{ $user->primaryRole()?->name ?? 'No role' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-border/70 p-6 text-sm text-muted-foreground">
                            No users found yet.
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
