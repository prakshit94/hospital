{{-- ✅ Cache user + permissions --}}
@php
    $user = auth()->user();
    $canUpdate = $user?->hasPermission('permissions.update');
    $canDelete = $user?->hasPermission('permissions.delete');
@endphp

<div class="table-toolbar">
    <div class="table-toolbar-copy">
        <div class="table-toolbar-title">Permission Catalog</div>
        <p>Manage permission groups, audit usage, and keep access rules consistent.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <span class="table-toolbar-stat">
            {{ $permissions->total() }} total permissions
        </span>
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
                    {{-- INDEX --}}
                    <td class="table-secondary font-mono text-xs">
                        {{ ($permissions->currentPage() - 1) * $permissions->perPage() + $loop->iteration }}
                    </td>

                    {{-- NAME --}}
                    <td data-label="Permission" class="table-primary">
                        {{ $permission->name }}
                    </td>

                    {{-- SLUG --}}
                    <td data-label="Slug" class="table-secondary">
                        {{ $permission->slug }}
                    </td>

                    {{-- GROUP --}}
                    <td data-label="Group">
                        <span class="ui-chip-muted">
                            {{ $permission->group_name }}
                        </span>
                    </td>

                    {{-- ROLES COUNT --}}
                    <td data-label="Roles" class="table-secondary">
                        {{ $permission->roles_count }}
                    </td>

                    {{-- STATUS --}}
                    <td data-label="Status">
                        @if($canUpdate)
                            <x-ui.toggle
                                :active="$permission->status === 'active'"
                                :action="route('permissions.toggle-status', $permission)"
                            />
                        @else
                            <span class="{{ $permission->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">
                                {{ ucfirst($permission->status) }}
                            </span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td data-label="Actions" class="actions-cell">
                        <div class="table-actions">

                            {{-- VIEW --}}
                            <x-ui.table-action 
                                href="{{ route('permissions.show', $permission) }}" 
                                label="View" 
                                data-modal-open
                            >
                                👁
                            </x-ui.table-action>

                            {{-- EDIT --}}
                            @if($canUpdate)
                                <x-ui.table-action 
                                    href="{{ route('permissions.edit', $permission) }}" 
                                    label="Edit" 
                                    tone="primary" 
                                    data-modal-open
                                >
                                    ✏️
                                </x-ui.table-action>
                            @endif

                            {{-- DELETE --}}
                            @if($canDelete)
                                <form 
                                    action="{{ route('permissions.destroy', $permission) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Are you sure you want to delete this permission?')" 
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <x-ui.table-action 
                                        type="submit" 
                                        label="Delete" 
                                        tone="danger"
                                    >
                                        🗑
                                    </x-ui.table-action>
                                </form>
                            @endif

                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            No permissions matched the current filters.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- PAGINATION --}}
<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

    <div class="text-sm text-muted-foreground">
        Showing 
        {{ $permissions->firstItem() ?? 0 }} 
        to 
        {{ $permissions->lastItem() ?? 0 }} 
        of 
        {{ $permissions->total() }} records.
        Page {{ $permissions->currentPage() }} of {{ $permissions->lastPage() }}.
    </div>

    <nav class="flex items-center gap-2 pagination">
        @if ($permissions->onFirstPage())
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </span>
        @else
            <a href="{{ $permissions->previousPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </a>
        @endif

        @if ($permissions->hasMorePages())
            <a href="{{ $permissions->nextPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            </span>
        @endif
    </nav>

</div>