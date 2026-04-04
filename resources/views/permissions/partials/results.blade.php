<div class="table-toolbar">
    <div class="table-toolbar-copy">
        <div class="table-toolbar-title">Permission Catalog</div>
        <p>Manage permission groups, audit usage, and keep access rules consistent.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <span class="table-toolbar-stat">{{ $permissions->total() }} total permissions</span>
        @if(auth()->user()?->hasPermission('permissions.create'))
            <x-ui.button href="{{ route('permissions.create') }}" data-modal-open>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
                Add Permission
            </x-ui.button>
        @endif
    </div>
</div>

<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th>Permission</th>
                <th>Slug</th>
                <th>Group</th>
                <th>Roles</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permissions as $permission)
                <tr>
                    <td data-label="Permission" class="table-primary">{{ $permission->name }}</td>
                    <td data-label="Slug" class="table-secondary">{{ $permission->slug }}</td>
                    <td data-label="Group"><span class="ui-chip-muted">{{ $permission->group_name }}</span></td>
                    <td data-label="Roles" class="table-secondary">{{ $permission->roles_count }}</td>
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
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
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
