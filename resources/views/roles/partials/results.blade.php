<div data-role-data="{{ $roles->mapWithKeys(fn($r) => [$r->id => $r->trashed()])->toJson() }}" x-data="{
    selected: [],
    selectAll: false,
    roleData: {},
    init() {
        this.roleData = JSON.parse(this.$el.dataset.roleData || '{}');
    },
    allIds() {
        return Object.keys(this.roleData);
    },
    toggleAll() {
        this.selected = this.selectAll ? this.allIds() : [];
    },
    isAnyDeleted() {
        return this.selected.some(id => this.roleData[id]);
    },
    bulkAction(action) {
        if (this.selected.length === 0) return;
        
        let confirmMsg = 'Are you sure you want to perform this action?';
        if (action === 'delete') confirmMsg = 'Are you sure you want to move selected roles to trash?';
        if (action === 'force-delete') confirmMsg = 'CRITICAL: This will PERMANENTLY delete selected records from the database. Continue?';
        if (action === 'restore') confirmMsg = 'Restore selected roles?';
        
        if (['delete', 'force-delete', 'restore'].includes(action) && !confirm(confirmMsg)) return;
        
        fetch('{{ route('roles.bulk-action') }}', {
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
        <div class="table-toolbar-title">Role Library</div>
        <p>Track reusable access profiles, permission counts, and assigned users.</p>
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
                @if(auth()->user()?->hasPermission('roles.delete'))
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
        <span class="table-toolbar-stat">{{ $roles->total() }} total roles</span>
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
                    <td>
                        <input type="checkbox" class="ui-checkbox row-checkbox" value="{{ $role->id }}" x-model="selected" @change="selectAll = selected.length === allIds().length">
                    </td>
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
                        @if($role->trashed())
                            <span class="ui-status-danger px-2 py-1 uppercase text-[10px] tracking-wide font-bold">Deleted</span>
                        @elseif(auth()->user()?->hasPermission('roles.update') && !$role->is_system)
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
                            
                            @if(!$role->trashed())
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
                            @else
                                @if(auth()->user()?->hasPermission('roles.delete'))
                                    <form action="{{ route('roles.restore', $role->id) }}" method="POST" onsubmit="return confirm('Restore this role?')" class="inline">
                                        @csrf
                                        <x-ui.table-action type="submit" label="Restore" tone="primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                                        </x-ui.table-action>
                                    </form>
                                    @if(!$role->is_system)
                                        <form action="{{ route('roles.force-delete', $role->id) }}" method="POST" onsubmit="return confirm('CRITICAL: Permanently delete this role?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.table-action type="submit" label="Purge" tone="danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/></svg>
                                            </x-ui.table-action>
                                        </form>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
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
    <nav class="flex items-center gap-2 pagination">
        @if ($roles->onFirstPage())
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </span>
        @else
            <a href="{{ $roles->previousPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </a>
        @endif

        @if ($roles->hasMorePages())
            <a href="{{ $roles->nextPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
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
