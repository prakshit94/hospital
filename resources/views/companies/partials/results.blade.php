<div class="table-toolbar">
    <div class="table-toolbar-copy" x-show="selected.length === 0">
        <div class="table-toolbar-title">Company List</div>
        <p>Review and manage business relationships.</p>
    </div>

    <!-- Bulk actions ribbon -->
    <div class="flex items-center gap-4 bg-primary/10 backdrop-blur-md py-2.5 px-5 rounded-2xl border border-primary/20 w-full shadow-lg shadow-primary/5" style="display: none;" x-show="selected.length > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="flex items-center gap-2">
            <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-primary text-[10px] font-black text-white shadow-sm" x-text="selected.length"></span>
            <span class="text-xs font-bold uppercase tracking-widest text-primary">Selected</span>
        </div>
        <div class="h-6 w-px bg-primary/20"></div>
        <div class="flex flex-1 items-center gap-2">
            @if(auth()->user()?->hasPermission('companies.delete'))
                <button type="button" x-show="isAnyDeleted()" @click="bulkAction('restore')" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500/10 px-3.5 py-2 text-[10px] font-black uppercase tracking-widest text-emerald-600 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                    Restore
                </button>
                <button type="button" x-show="!isAnyDeleted()" @click="bulkAction('delete')" class="inline-flex items-center gap-2 rounded-xl bg-destructive/10 px-3.5 py-2 text-[10px] font-black uppercase tracking-widest text-destructive border border-destructive/20 hover:bg-destructive/20 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/></svg>
                    Delete
                </button>
                <button type="button" x-show="isAnyDeleted()" @click="bulkAction('force-delete')" class="inline-flex items-center gap-2 rounded-xl bg-destructive/20 px-3.5 py-2 text-[10px] font-black uppercase tracking-widest text-destructive border border-destructive/30 hover:bg-destructive/30 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/></svg>
                    Purge
                </button>
            @endif
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3" x-show="selected.length === 0">
        <span class="table-toolbar-stat">{{ $companies->total() }} total companies</span>
    </div>
</div>

<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th class="w-10">
                    <input type="checkbox" class="ui-checkbox" x-model="selectAll" @change="toggleAll">
                </th>
                <th>Company Information</th>
                <th>Identifier</th>
                <th>Contact Point</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
                <tr class="group transition hover:bg-secondary/20 {{ $company->trashed() ? 'opacity-70 grayscale-[0.3]' : '' }}">
                    <td>
                        <input type="checkbox" class="ui-checkbox row-checkbox" value="{{ $company->id }}" x-model="selected" @change="selectAll = selected.length === allIds().length">
                    </td>
                    <td data-label="Company">
                        <div class="table-primary">{{ $company->name }}</div>
                        <div class="mt-0.5 text-xs text-muted-foreground">{{ $company->address }}</div>
                    </td>
                    <td data-label="Code">
                        <span class="font-mono text-xs font-bold text-primary">{{ $company->code ?: '---' }}</span>
                    </td>
                    <td data-label="Contact">
                        <div class="table-primary text-xs">{{ $company->contact_person }}</div>
                        <div class="mt-0.5 text-[10px] text-muted-foreground">{{ $company->contact_number }}</div>
                    </td>
                    <td data-label="Status">
                        @if($company->trashed())
                            <span class="ui-status-danger">Deleted</span>
                        @else
                            <span class="ui-chip {{ $company->is_active ? '!bg-emerald-500/10 !text-emerald-600' : '!bg-amber-500/10 !text-amber-600' }}">
                                {{ $company->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        @endif
                    </td>
                    <td data-label="Actions" class="actions-cell">
                        <div class="relative flex justify-end" x-data="{ open: false }">
                            <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-center rounded-lg p-2 text-muted-foreground transition hover:bg-secondary hover:text-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                            </button>
                            <div x-show="open" x-cloak x-transition.opacity.duration.200ms class="absolute right-0 top-full z-10 mt-1 min-w-[180px] overflow-hidden rounded-[1.2rem] border border-border bg-popover p-1.5 shadow-xl">
                                @if(!$company->trashed())
                                    <a href="{{ route('companies.show', $company->id) }}" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">View</a>
                                    <a href="{{ route('companies.edit', $company->id) }}" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">Edit</a>
                                    @if(auth()->user()?->hasPermission('companies.delete'))
                                        <div class="my-1 border-t border-border/50"></div>
                                        <form action="{{ route('companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Delete this company?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold text-danger transition hover:bg-danger/10">Delete</button>
                                        </form>
                                    @endif
                                @else
                                    @if(auth()->user()?->hasPermission('companies.delete'))
                                        <form action="{{ route('companies.restore', $company->id) }}" method="POST" onsubmit="return confirm('Restore this company?')">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-500/10">Restore</button>
                                        </form>
                                        <form action="{{ route('companies.force-delete', $company->id) }}" method="POST" onsubmit="return confirm('CRITICAL: Permanently delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold text-danger transition hover:bg-danger/10">Purge</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6"><div class="empty-state">No companies found.</div></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="text-sm text-muted-foreground">
        Showing {{ $companies->firstItem() ?? 0 }} to {{ $companies->lastItem() ?? 0 }} of {{ $companies->total() }} records.
    </div>
    <nav class="flex items-center gap-2 pagination">
        @if ($companies->onFirstPage())
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary opacity-50">Previous</span>
        @else
            <a href="{{ $companies->previousPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary hover:bg-accent transition">Previous</a>
        @endif

        @if ($companies->hasMorePages())
            <a href="{{ $companies->nextPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary hover:bg-accent transition">Next</a>
        @else
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary opacity-50">Next</span>
        @endif
    </nav>
</div>
