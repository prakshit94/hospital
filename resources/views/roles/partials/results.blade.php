<div class="table-toolbar">
    <div class="table-toolbar-copy">
        <div class="table-toolbar-title">Role Library</div>
        <p>Track reusable access profiles, permission counts, and assigned users.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <span class="table-toolbar-stat">{{ $roles->total() }} total roles</span>
    </div>
</div>

<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th class="w-10">#</th>
                <th>Role</th>
                <th>Permissions</th>
                <th>Users</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td class="table-secondary font-mono text-xs">{{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}</td>
                    <td data-label="Role">
                        <div>
                            <div class="table-primary">{{ $role->name }}</div>
                            <div class="mt-1 text-xs text-muted-foreground">{{ $role->slug }}</div>
                        </div>
                    </td>
                    <td data-label="Permissions" class="table-secondary">
                        <span class="font-semibold text-foreground">{{ $role->permissions_count }}</span>
                        <span class="text-xs text-muted-foreground">assigned</span>
                    </td>
                    <td data-label="Users">
                        <span class="font-semibold text-foreground">{{ $role->users_count }}</span>
                        <span class="text-xs text-muted-foreground">active</span>
                    </td>
                    <td data-label="Status">
                        @if(auth()->user()?->hasPermission('roles.update') && !$role->is_system)
                            <x-ui.toggle
                                :active="$role->status === 'active'"
                                :action="route('roles.toggle-status', $role)"
                            />
                        @else
                            <span class="{{ $role->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">
                                {{ $role->status }}
                            </span>
                        @endif
                    </td>
                    <td data-label="Actions" class="actions-cell">
                        <div class="table-actions">
                            <x-ui.table-action href="{{ route('roles.show', $role) }}" label="View" data-modal-open>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2.06 12.35a1 1 0 0 1 0-.7C3.42 8.6 6.47 6 12 6s8.58 2.6 9.94 5.65a1 1 0 0 1 0 .7C20.58 15.4 17.53 18 12 18s-8.58-2.6-9.94-5.65Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </x-ui.table-action>
                            @if(auth()->user()?->hasPermission('roles.update'))
                                <x-ui.table-action href="{{ route('roles.edit', $role) }}" label="Edit" tone="primary" data-modal-open>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"/>
                                        <path d="m16.5 3.5 4 4L7 21H3v-4z"/>
                                    </svg>
                                </x-ui.table-action>
                            @endif
                            @if(auth()->user()?->hasPermission('roles.delete') && !$role->is_system)
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.table-action type="submit" label="Delete" tone="danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/>
                                        </svg>
                                    </x-ui.table-action>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">No roles matched the current filters.</div>
                    </td>
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
