@extends('layouts.app')

@php
    $pageTitle = 'User Profile';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="mb-3 inline-flex rounded-xl px-3 py-1 text-[11px] font-black uppercase tracking-[0.2em] {{ $user->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-300' : 'bg-rose-500/10 text-rose-600 dark:text-rose-300' }}">{{ $user->status }} account</div>
                <h1 class="font-heading text-3xl font-black tracking-tight">{{ $user->name }}</h1>
                <p class="mt-2 text-sm text-muted-foreground">{{ $user->email }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()?->hasPermission('users.update'))
                    <x-ui.button variant="secondary" href="{{ route('users.edit', $user) }}">Edit</x-ui.button>
                @endif
                @if(auth()->user()?->hasPermission('users.delete') && auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <x-ui.button onclick="return confirm('Delete this user?')">Delete</x-ui.button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-ui.card class="xl:col-span-2">
                <div class="mb-6">
                    <h2 class="font-heading text-xl font-bold">Account Overview</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Roles, current state, and login history for this account.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">Email</div>
                        <div class="mt-2 text-sm font-semibold text-foreground">{{ $user->email }}</div>
                    </div>
                    <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">Last Login</div>
                        <div class="mt-2 text-sm font-semibold text-foreground">{{ $user->last_login_at?->format('d M Y h:i A') ?? 'Never' }}</div>
                    </div>
                    <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4 md:col-span-2">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">Roles</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <h2 class="font-heading text-xl font-bold">Effective Permissions</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Inherited through the attached roles.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->permissionSlugs() as $permission)
                        <span class="rounded-xl bg-secondary px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-muted-foreground">{{ $permission }}</span>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <x-ui.card>
            <div class="mb-5">
                <h2 class="font-heading text-xl font-bold">Recent Activity</h2>
                <p class="text-sm text-muted-foreground">Recent events performed by or against this user.</p>
            </div>
            <div class="space-y-3">
                @forelse($activities as $activity)
                    <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                                <div class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">{{ $activity->action }}</div>
                            </div>
                            <div class="text-xs text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-border/70 p-6 text-sm text-muted-foreground">
                        No activity recorded yet.
                    </div>
                @endforelse
            </div>
        </x-ui.card>
    </div>
@endsection
