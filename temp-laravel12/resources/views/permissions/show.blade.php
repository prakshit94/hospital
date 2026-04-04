@extends('layouts.app')

@php
    $pageTitle = 'Permission Details';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="mb-3 inline-flex rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.2em] text-primary">{{ $permission->group_name }}</div>
                <h1 class="font-heading text-3xl font-black tracking-tight">{{ $permission->name }}</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">{{ $permission->description ?: 'No description provided for this permission.' }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()?->hasPermission('permissions.update'))
                    <x-ui.button variant="secondary" href="{{ route('permissions.edit', $permission) }}">Edit</x-ui.button>
                @endif
                @if(auth()->user()?->hasPermission('permissions.delete'))
                    <form method="POST" action="{{ route('permissions.destroy', $permission) }}">
                        @csrf
                        @method('DELETE')
                        <x-ui.button onclick="return confirm('Delete this permission?')">Delete</x-ui.button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-ui.card class="xl:col-span-2">
                <div class="mb-6">
                    <h2 class="font-heading text-xl font-bold">Permission Metadata</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Operational details and the roles that currently grant this ability.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">Slug</div>
                        <div class="mt-2 text-sm font-semibold text-foreground">{{ $permission->slug }}</div>
                    </div>
                    <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">Group</div>
                        <div class="mt-2 text-sm font-semibold text-foreground">{{ $permission->group_name }}</div>
                    </div>
                    <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4 md:col-span-2">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">Roles Using This Permission</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($permission->roles as $role)
                                <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <h2 class="font-heading text-xl font-bold">Recent Activity</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Changes affecting this permission.</p>
                </div>
                <div class="space-y-3">
                    @forelse($activities as $activity)
                        <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4 text-sm">
                            <div class="font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                            <div class="mt-1 text-xs text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-border/70 p-6 text-sm text-muted-foreground">No activity recorded for this permission.</div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
