@extends('layouts.app')

@php
    $pageTitle = 'Role Details';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="mb-3 inline-flex rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.2em] text-primary">{{ $role->slug }}</div>
                <h1 class="font-heading text-3xl font-black tracking-tight">{{ $role->name }}</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">{{ $role->description ?: 'No description provided for this role.' }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()?->hasPermission('roles.update'))
                    <x-ui.button variant="secondary" href="{{ route('roles.edit', $role) }}">Edit</x-ui.button>
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

        <div class="grid gap-6 xl:grid-cols-3">
            <x-ui.card class="xl:col-span-2">
                <div class="mb-6">
                    <h2 class="font-heading text-xl font-bold">Permissions</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Capabilities granted to users assigned to this role.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach($role->permissions as $permission)
                        <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $permission->slug }}</span>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <h2 class="font-heading text-xl font-bold">Assigned Users</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Users currently operating under this role.</p>
                </div>
                <div class="space-y-3">
                    @foreach($role->users as $user)
                        <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4 text-sm">
                            <div class="font-semibold text-foreground">{{ $user->name }}</div>
                            <div class="mt-1 text-muted-foreground">{{ $user->email }}</div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <x-ui.card>
            <div class="mb-5">
                <h2 class="font-heading text-xl font-bold">Recent Activity</h2>
                <p class="text-sm text-muted-foreground">Audit entries affecting this role.</p>
            </div>
            <div class="space-y-3">
                @forelse($activities as $activity)
                    <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                        <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                        <div class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-border/70 p-6 text-sm text-muted-foreground">No activity recorded for this role.</div>
                @endforelse
            </div>
        </x-ui.card>
    </div>
@endsection
