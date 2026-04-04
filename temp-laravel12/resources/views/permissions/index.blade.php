@extends('layouts.app')

@php
    $pageTitle = 'Permissions';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Permissions</h1>
                <p class="mt-2 text-sm text-muted-foreground">Manage the individual abilities that can be attached to roles.</p>
            </div>
            @if(auth()->user()?->hasPermission('permissions.create'))
                <x-ui.button href="{{ route('permissions.create') }}">Create Permission</x-ui.button>
            @endif
        </div>

        <x-ui.card>
            <form method="GET" class="mb-5 grid gap-3 lg:grid-cols-[1fr_auto]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search permissions..." class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                <x-ui.button class="justify-center">Filter</x-ui.button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="border-b border-border/60 text-[11px] font-black uppercase tracking-[0.22em] text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Permission</th>
                            <th class="px-4 py-3">Slug</th>
                            <th class="px-4 py-3">Group</th>
                            <th class="px-4 py-3">Roles</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($permissions as $permission)
                            <tr class="transition hover:bg-secondary/25">
                                <td class="px-4 py-4 font-semibold text-foreground">{{ $permission->name }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $permission->slug }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $permission->group_name }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $permission->roles_count }}</td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <x-ui.button variant="ghost" href="{{ route('permissions.show', $permission) }}">View</x-ui.button>
                                        @if(auth()->user()?->hasPermission('permissions.update'))
                                            <x-ui.button variant="secondary" href="{{ route('permissions.edit', $permission) }}">Edit</x-ui.button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-muted-foreground">No permissions matched the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $permissions->links() }}</div>
        </x-ui.card>
    </div>
@endsection
