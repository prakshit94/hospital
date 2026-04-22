<div data-user-data="{{ $users->mapWithKeys(fn($u) => [$u->id => $u->trashed()])->toJson() }}" x-data="{
    selected: [],
    selectAll: false,
    userData: {},
    init() {
        this.userData = JSON.parse(this.$el.dataset.userData || '{}');
    },
    allIds() {
        return Object.keys(this.userData);
    },
    toggleAll() {
        this.selected = this.selectAll ? this.allIds() : [];
    },
    isAnyDeleted() {
        return this.selected.some(id => this.userData[id]);
    },
    isAnyActive() {
        return this.selected.some(id => !this.userData[id]);
    },
    bulkAction(action) {
        if (this.selected.length === 0) return;
        
        let confirmMsg = 'Are you sure you want to perform this action?';
        if (action === 'delete') confirmMsg = 'Are you sure you want to move selected users to trash?';
        if (action === 'force-delete') confirmMsg = 'CRITICAL: This will PERMANENTLY delete selected records from the database. This cannot be undone. Continue?';
        if (action === 'restore') confirmMsg = 'Restore selected users to active status?';
        
        if (['delete', 'force-delete', 'restore'].includes(action) && !confirm(confirmMsg)) return;
        
        fetch('{{ route('users.bulk-action') }}', {
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
        <div class="table-toolbar-title">User Directory</div>
        <p>Review account access, status, and role assignments in one place.</p>
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
                @if(auth()->user()?->hasPermission('users.update'))
                    <button type="button" x-show="isAnyActive()" @click="bulkAction('active'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                        <span class="size-2 rounded-full bg-emerald-500"></span> Mark Active
                    </button>
                    <button type="button" x-show="isAnyActive()" @click="bulkAction('inactive'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                        <span class="size-2 rounded-full bg-slate-400"></span> Mark Inactive
                    </button>
                @endif
                @if(auth()->user()?->hasPermission('users.delete'))
                    <div class="my-1 border-t border-border/50"></div>
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
        <span class="table-toolbar-stat">{{ $users->total() }} total users</span>
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
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Status</th>
                <th>Last Login</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <input type="checkbox" class="ui-checkbox row-checkbox" value="{{ $user->id }}" x-model="selected" @change="selectAll = selected.length === allIds().length">
                    </td>
                    <td class="table-secondary font-mono text-xs">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                    <td data-label="Name">
                        <div class="flex items-center gap-3">
                            <div class="relative shrink-0">
                                @if($user->profile_image)
                                    <img src="{{ $user->profile_image }}" alt="Profile" class="h-10 w-10 rounded-full object-cover shadow-sm bg-secondary">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary font-bold shadow-sm">
                                        {{ substr($user->name ?: 'U', 0, 1) }}
                                    </div>
                                @endif
                                <span class="absolute right-0 bottom-0 block h-2.5 w-2.5 rounded-full ring-2 ring-card {{ $user->is_online ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                            </div>
                            <div>
                                <div class="table-primary">{{ $user->name ?: trim("{$user->first_name} {$user->last_name}") }}</div>
                                <div class="mt-0.5 text-xs text-muted-foreground">{{ $user->job_title ?: 'User #' . $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td data-label="Email" class="table-secondary">{{ $user->email }}</td>
                    <td data-label="Roles">
                        <div class="flex flex-wrap gap-2 md:justify-end lg:justify-start">
                            @forelse($user->roles as $role)
                                <span class="ui-chip">{{ $role->name }}</span>
                            @empty
                                <span class="ui-chip-muted">No role</span>
                            @endforelse
                        </div>
                    </td>
                    <td data-label="Status">
                        @if($user->trashed())
                            <span class="ui-status-danger px-2 py-1 uppercase text-[10px] tracking-wide font-bold">Deleted</span>
                        @elseif(auth()->user()?->hasPermission('users.update') && auth()->id() !== $user->id)
                            <x-ui.toggle
                                :active="$user->status === 'active'"
                                :action="route('users.toggle-status', $user)"
                            />
                        @else
                            <span class="{{ $user->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">
                                {{ $user->status }}
                            </span>
                        @endif
                    </td>
                    <td data-label="Last Login" class="table-secondary">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                    <td data-label="Actions" class="actions-cell">
                        <div class="relative flex justify-end" x-data="{ open: false }">
                            <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-center rounded-lg p-2 text-muted-foreground transition hover:bg-secondary hover:text-foreground focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition.opacity.duration.200ms class="absolute right-0 top-full z-10 mt-1 min-w-[160px] overflow-hidden rounded-[1.2rem] border border-border bg-popover p-1.5 shadow-[0_12px_24px_-10px_rgba(15,23,42,0.2)]">
                                <a href="{{ route('users.show', $user) }}" data-modal-open class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M2.06 12.35a1 1 0 0 1 0-.7C3.42 8.6 6.47 6 12 6s8.58 2.6 9.94 5.65a1 1 0 0 1 0 .7C20.58 15.4 17.53 18 12 18s-8.58-2.6-9.94-5.65Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    View Details
                                </a>
                                @if(auth()->user()?->hasPermission('users.update'))
                                    <a href="{{ route('users.edit', $user) }}" data-modal-open class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M12 20h9"/><path d="m16.5 3.5 4 4L7 21H3v-4z"/>
                                        </svg>
                                        Edit User
                                    </a>
                                @endif
                                @if(auth()->user()?->hasPermission('users.delete') && auth()->id() !== $user->id)
                                    <div class="my-1 border-t border-border/50"></div>
                                    @if($user->trashed())
                                        <form action="{{ route('users.restore', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to restore this user?')" class="block">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-emerald-600 transition hover:bg-emerald-500/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                                </svg>
                                                Restore User
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/>
                                                </svg>
                                                Delete User
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">No users matched the current filters.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="text-sm text-muted-foreground">
        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} records.
        Page {{ $users->currentPage() }} of {{ $users->lastPage() }}.
    </div>
    <nav class="flex items-center gap-2 pagination">
        @if ($users->onFirstPage())
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </span>
        @else
            <a href="{{ $users->previousPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </a>
        @endif

        @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
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
