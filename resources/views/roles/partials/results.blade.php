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

<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="text-sm text-muted-foreground">
        Showing {{ $roles->firstItem() ?? 0 }} to {{ $roles->lastItem() ?? 0 }} of {{ $roles->total() }} records.
        Page {{ $roles->currentPage() }} of {{ $roles->lastPage() }}.
    </div>
    <div>{{ $roles->links() }}</div>
</div>
