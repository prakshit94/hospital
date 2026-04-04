@extends('layouts.app')

@php
    $pageTitle = 'Roles';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Roles</h1>
                <p class="mt-2 text-sm text-muted-foreground">Create and maintain reusable access profiles for your team.</p>
            </div>
            @if(auth()->user()?->hasPermission('roles.create'))
                <x-ui.button href="{{ route('roles.create') }}">Create Role</x-ui.button>
            @endif
        </div>

        <x-ui.card>
            <form method="GET" class="mb-5 grid gap-3 lg:grid-cols-[1fr_auto]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search roles..." class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                <x-ui.button class="justify-center">Filter</x-ui.button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="border-b border-border/60 text-[11px] font-black uppercase tracking-[0.22em] text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Slug</th>
                            <th class="px-4 py-3">Permissions</th>
                            <th class="px-4 py-3">Users</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($roles as $role)
                            <tr class="transition hover:bg-secondary/25">
                                <td class="px-4 py-4 font-semibold text-foreground">
                                    {{ $role->name }}
                                    @if($role->is_system)
                                        <span class="ml-2 rounded-xl bg-primary/10 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-primary">System</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $role->slug }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $role->permissions_count }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $role->users_count }}</td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <x-ui.button variant="ghost" href="{{ route('roles.show', $role) }}">View</x-ui.button>
                                        @if(auth()->user()?->hasPermission('roles.update'))
                                            <x-ui.button variant="secondary" href="{{ route('roles.edit', $role) }}">Edit</x-ui.button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-muted-foreground">No roles matched the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $roles->links() }}</div>
        </x-ui.card>
    </div>
@endsection
