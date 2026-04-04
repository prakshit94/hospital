@extends('layouts.app')

@php
    $pageTitle = 'Users';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Users</h1>
                <p class="mt-2 text-sm text-muted-foreground">Manage sign-in access, account state, and role assignments.</p>
            </div>
            @if(auth()->user()?->hasPermission('users.create'))
                <x-ui.button href="{{ route('users.create') }}">Create User</x-ui.button>
            @endif
        </div>

        <x-ui.card>
            <form method="GET" class="mb-5 grid gap-3 lg:grid-cols-[1fr_auto_auto_auto]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                <select name="status" class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
                <select name="role" class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    <option value="">All roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected((string) request('role') === (string) $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                <x-ui.button class="justify-center">Filter</x-ui.button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="border-b border-border/60 text-[11px] font-black uppercase tracking-[0.22em] text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Roles</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Last Login</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($users as $user)
                            <tr class="transition hover:bg-secondary/25">
                                <td class="px-4 py-4 font-semibold text-foreground">{{ $user->name }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $user->email }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->roles as $role)
                                            <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="rounded-xl px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] {{ $user->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-300' : 'bg-rose-500/10 text-rose-600 dark:text-rose-300' }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <x-ui.button variant="ghost" href="{{ route('users.show', $user) }}">View</x-ui.button>
                                        @if(auth()->user()?->hasPermission('users.update'))
                                            <x-ui.button variant="secondary" href="{{ route('users.edit', $user) }}">Edit</x-ui.button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-muted-foreground">No users matched the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $users->links() }}</div>
        </x-ui.card>
    </div>
@endsection
