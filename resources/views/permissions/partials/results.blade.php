@php
    $user = auth()->user();
    $canUpdate = $user?->hasPermission('permissions.update');
    $canDelete = $user?->hasPermission('permissions.delete');
@endphp

<div data-permission-data="{{ $permissions->mapWithKeys(fn($p) => [$p->id => $p->trashed()])->toJson() }}" x-data="{
    selected: [],
    selectAll: false,
    permissionData: {},
    init() {
        this.permissionData = JSON.parse(this.$el.dataset.permissionData || '{}');
    },
    allIds() {
        return Object.keys(this.permissionData);
    },
    toggleAll() {
        this.selected = this.selectAll ? this.allIds() : [];
    },
    isAnyDeleted() {
        return this.selected.some(id => this.permissionData[id]);
    },
    bulkAction(action) {
        if (this.selected.length === 0) return;
        
        let confirmMsg = 'Are you sure you want to perform this action?';
        if (action === 'delete') confirmMsg = 'Are you sure you want to move selected permissions to trash?';
        if (action === 'force-delete') confirmMsg = 'CRITICAL: This will PERMANENTLY delete selected records from the database. Continue?';
        if (action === 'restore') confirmMsg = 'Restore selected permissions?';
        
        if (['delete', 'force-delete', 'restore'].includes(action) && !confirm(confirmMsg)) return;
        
        fetch('{{ route('permissions.bulk-action') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ action: action, ids: this.selected })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'success', title: 'Success', description: data.message }}));
                const searchForm = document.querySelector('form[data-async-search]');
                if (searchForm) searchForm.dispatchEvent(new Event('submit'));
                else window.location.reload();
            } else {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'error', title: 'Error', description: data.message }}));
            }
            this.selected = [];
            this.selectAll = false;
        });
    }
}">
<div class="table-toolbar">
    <div class="table-toolbar-copy" x-show="selected.length === 0">
        <div class="table-toolbar-title">Permission Catalog</div>
        <p>Manage permission groups, audit usage, and keep access rules consistent.</p>
    </div>

    <!-- Bulk actions ribbon -->
    <div class="flex items-center gap-4 bg-primary/5 py-2 px-4 rounded-lg border border-primary/20 w-full" style="display: none;" x-show="selected.length > 0">
        <span class="text-sm font-semibold text-primary"><span x-text="selected.length"></span> selected</span>
        <div class="h-4 w-px bg-border"></div>
        <div class="relative flex" x-data="{ openBulk: false }">
            <x-ui.button variant="secondary" size="sm" @click="openBulk = !openBulk" @click.away="openBulk = false" class="gap-2">
                Actions
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </x-ui.button>
            <div x-show="openBulk" x-cloak x-transition.opacity.duration.200ms class="absolute left-0 top-full z-20 mt-1 min-w-[180px] overflow-hidden rounded-[1.2rem] border border-border bg-popover p-1.5 shadow-[0_12px_24px_-10px_rgba(15,23,42,0.2)]">
                @if($canDelete)
                    <button type="button" x-show="isAnyDeleted()" @click="bulkAction('restore'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-emerald-600 transition hover:bg-emerald-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Restore Selected
                    </button>
                    <button type="button" x-show="!isAnyDeleted()" @click="bulkAction('delete'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/></svg> Delete Selected
                    </button>
                    <button type="button" x-show="isAnyDeleted()" @click="bulkAction('force-delete'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/></svg> Permanent Delete
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3" x-show="selected.length === 0">
        <span class="table-toolbar-stat">
            {{ $permissions->total() }} total permissions
        </span>
    </div>
</div>

<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th class="w-10">
                    <input type="checkbox" class="ui-checkbox" x-model="selectAll" @change="toggleAll">
                </th>
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
                    <td>
                        <input type="checkbox" class="ui-checkbox row-checkbox" value="{{ $permission->id }}" x-model="selected" @change="selectAll = selected.length === allIds().length">
                    </td>
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
                        @if($permission->trashed())
                            <span class="ui-status-danger px-2 py-1 uppercase text-[10px] tracking-wide font-bold">Deleted</span>
                        @elseif($canUpdate)
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

                            @if(!$permission->trashed())
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
                            @else
                                @if($canDelete)
                                    <form action="{{ route('permissions.restore', $permission->id) }}" method="POST" onsubmit="return confirm('Restore this permission?')" class="inline">
                                        @csrf
                                        <x-ui.table-action type="submit" label="Restore" tone="primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                                        </x-ui.table-action>
                                    </form>
                                    <form action="{{ route('permissions.force-delete', $permission->id) }}" method="POST" onsubmit="return confirm('CRITICAL: Permanently delete this permission?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.table-action type="submit" label="Purge" tone="danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/></svg>
                                        </x-ui.table-action>
                                    </form>
                                @endif
                            @endif

                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="8">
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
</div>
nav>

</div>