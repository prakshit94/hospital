<div class="table-toolbar">
    <div class="table-toolbar-copy">
        <div class="table-toolbar-title">Permission Catalog</div>
        <p>Manage permission groups, audit usage, and keep access rules consistent.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <span class="table-toolbar-stat">{{ $permissions->total() }} total permissions</span>
    </div>
</div>

<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th class="w-10">#</th>
                <th>Permission</th>
                <th>Slug</th>
                <th>Group</th>
                <th>Roles</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permissions as $permission)
                <tr>
                    <td class="table-secondary font-mono text-xs">{{ ($permissions->currentPage() - 1) * $permissions->perPage() + $loop->iteration }}</td>
                    <td data-label="Permission" class="table-primary">{{ $permission->name }}</td>
                    <td data-label="Slug" class="table-secondary">{{ $permission->slug }}</td>
                    <td data-label="Group"><span class="ui-chip-muted">{{ $permission->group_name }}</span></td>
                    <td data-label="Roles" class="table-secondary">{{ $permission->roles_count }}</td>
                    <td data-label="Status">
                        @if(auth()->user()?->hasPermission('permissions.update'))
                            <x-ui.toggle
                                :active="$permission->status === 'active'"
                                :action="route('permissions.toggle-status', $permission)"
                            />
                        @else
                            <span class="{{ $permission->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">
                                {{ $permission->status }}
                            </span>
                        @endif
                    </td>
                    <td data-label="Actions" class="actions-cell">
                        <div class="table-actions">
                            <x-ui.table-action href="{{ route('permissions.show', $permission) }}" label="View" data-modal-open>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2.06 12.35a1 1 0 0 1 0-.7C3.42 8.6 6.47 6 12 6s8.58 2.6 9.94 5.65a1 1 0 0 1 0 .7C20.58 15.4 17.53 18 12 18s-8.58-2.6-9.94-5.65Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </x-ui.table-action>
                            @if(auth()->user()?->hasPermission('permissions.update'))
                                <x-ui.table-action href="{{ route('permissions.edit', $permission) }}" label="Edit" tone="primary" data-modal-open>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"/>
                                        <path d="m16.5 3.5 4 4L7 21H3v-4z"/>
                                    </svg>
                                </x-ui.table-action>
                            @endif
                            @if(auth()->user()?->hasPermission('permissions.delete'))
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permission?')" class="inline">
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
                    <td colspan="7">
                        <div class="empty-state">No permissions matched the current filters.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="text-sm text-muted-foreground">
        Showing {{ $permissions->firstItem() ?? 0 }} to {{ $permissions->lastItem() ?? 0 }} of {{ $permissions->total() }} records.
        Page {{ $permissions->currentPage() }} of {{ $permissions->lastPage() }}.
    </div>
    <div>{{ $permissions->links() }}</div>
</div>
