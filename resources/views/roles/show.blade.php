@extends('layouts.app')

@php
    $pageTitle = 'Role Details';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="ui-chip">{{ $role->slug }}</span>
                    <h1 class="hero-title">{{ $role->name }}</h1>
                    <p class="hero-copy">{{ $role->description ?: 'No description provided for this role.' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if(auth()->user()?->hasPermission('roles.update'))
                        <x-ui.button variant="secondary" href="{{ route('roles.edit', $role) }}" data-modal-open>Edit</x-ui.button>
                    @endif
                    @if(auth()->user()?->hasPermission('roles.delete') && !$role->is_system)
                        <form method="POST" action="{{ route('roles.destroy', $role) }}">
                            @csrf
                            @method('DELETE')
                            <x-ui.button onclick="return confirm('Delete this role?')">Delete</x-ui.button>
                        </form>
                    @endif
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Permissions</div>
                        <h2 class="section-title">Granted abilities</h2>
                        <p class="section-copy">Capabilities granted to users assigned to this role.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($role->permissions as $permission)
                        <span class="ui-chip">{{ $permission->slug }}</span>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Assigned Users</div>
                    <h2 class="section-title">People using this role</h2>
                    <p class="section-copy">Users currently operating under this role.</p>
                </div>
                <div class="space-y-3">
                    @foreach($role->users as $user)
                        <div class="list-card text-sm">
                            <div class="font-semibold text-foreground">{{ $user->name }}</div>
                            <div class="mt-1 text-muted-foreground">{{ $user->email }}</div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </section>

        <x-ui.card>
            <div class="section-header">
                <div>
                    <div class="section-kicker">Recent Activity</div>
                    <h2 class="section-title">Role change history</h2>
                    <p class="section-copy">Audit entries affecting this role.</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($activities as $activity)
                    <div class="list-card">
                        <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                        <div class="mt-2 text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="empty-state">No activity recorded for this role.</div>
                @endforelse
            </div>
        </x-ui.card>
    </div>
@endsection
