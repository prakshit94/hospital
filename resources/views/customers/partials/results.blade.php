<div data-customer-data="{{ $customers->mapWithKeys(fn($c) => [$c->uuid => $c->trashed()])->toJson() }}" x-data="{
    selected: [],
    selectAll: false,
    customerData: {},
    init() {
        this.customerData = JSON.parse(this.$el.dataset.customerData || '{}');
    },
    allIds() {
        return Object.keys(this.customerData);
    },
    toggleAll() {
        this.selected = this.selectAll ? this.allIds() : [];
    },
    isAnyDeleted() {
        return this.selected.some(id => this.customerData[id]);
    },
    isAnyActive() {
        return this.selected.some(id => !this.customerData[id]);
    },
    bulkAction(action) {
        if (this.selected.length === 0) return;
        
        let confirmMsg = 'Are you sure you want to perform this action?';
        if (action === 'delete') confirmMsg = 'Are you sure you want to move selected records to archive?';
        if (action === 'restore') confirmMsg = 'Restore selected records to active status?';
        if (action === 'force-delete') confirmMsg = 'PERMANENTLY DELETE selected records? This action cannot be undone.';
        
        if (['delete', 'restore', 'force-delete'].includes(action) && !confirm(confirmMsg)) return;
        
        fetch('{{ route('customers.bulk-action') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ action: action, ids: this.selected })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'success', title: data.message }}));
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
        <div class="table-toolbar-title">Customer Directory</div>
        <p>Review customer accounts, territories, and business status in one place.</p>
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
                <button type="button" x-show="isAnyActive()" @click="bulkAction('active'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                    <span class="size-2 rounded-full bg-emerald-500"></span> Mark Active
                </button>
                <button type="button" x-show="isAnyActive()" @click="bulkAction('inactive'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                    <span class="size-2 rounded-full bg-slate-400"></span> Mark Inactive
                </button>
                <div class="my-1 border-t border-border/50"></div>
                <button type="button" x-show="isAnyDeleted()" @click="bulkAction('restore'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-emerald-600 transition hover:bg-emerald-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Restore Selected
                </button>
                <button type="button" x-show="!isAnyDeleted()" @click="bulkAction('delete'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m3 6 18 0m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/></svg> Archive Selected
                </button>
                <button type="button" x-show="isAnyDeleted()" @click="bulkAction('force-delete'); openBulk = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/></svg> Permanent Delete
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3" x-show="selected.length === 0">
        <span class="table-toolbar-stat">{{ $customers->total() }} total customers</span>
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
                <th>Contact</th>
                <th>Type/Category</th>
                <th>Status</th>
                <th>Finance</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr class="{{ $customer->trashed() ? 'opacity-60 grayscale-[0.3]' : '' }}">
                    <td>
                        <input type="checkbox" class="ui-checkbox row-checkbox" value="{{ $customer->uuid }}" x-model="selected" @change="selectAll = selected.length === allIds().length">
                    </td>
                    <td class="table-secondary font-mono text-xs">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                    <td data-label="Name">
                        <div class="flex items-center gap-3">
                            <div class="relative shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary font-bold shadow-sm">
                                    {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                                </div>
                                <span class="absolute right-0 bottom-0 block h-2.5 w-2.5 rounded-full ring-2 ring-card {{ $customer->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                            </div>
                            <div>
                                <div class="table-primary">{{ $customer->display_name }}</div>
                                <div class="mt-0.5 text-xs text-muted-foreground">{{ $customer->customer_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td data-label="Contact">
                        <div class="table-primary">{{ $customer->mobile }}</div>
                        <div class="text-xs text-muted-foreground">{{ $customer->email ?: 'No email' }}</div>
                    </td>
                    <td data-label="Type/Category">
                        <div class="flex flex-wrap gap-2 md:justify-end lg:justify-start">
                            <span class="ui-chip">{{ ucfirst($customer->type) }}</span>
                            <span class="badge-secondary text-[10px]">{{ ucfirst($customer->category) }}</span>
                        </div>
                    </td>
                    <td data-label="Status">
                        @if($customer->trashed())
                            <span class="ui-status-danger px-2 py-1 uppercase text-[10px] tracking-wide font-bold">Deleted</span>
                        @else
                            <x-ui.toggle
                                :active="$customer->status === 'active'"
                                :action="route('customers.toggle-status', $customer->uuid)"
                            />
                        @endif
                    </td>
                    <td data-label="Finance" class="text-right">
                        <div class="table-primary font-bold">₹{{ number_format($customer->outstanding_balance, 2) }}</div>
                        <div class="text-xs text-muted-foreground">Limit: ₹{{ number_format($customer->credit_limit, 2) }}</div>
                    </td>
                    <td data-label="Actions" class="actions-cell">
                        <div class="relative flex justify-end" x-data="{ open: false }">
                            <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-center rounded-lg p-2 text-muted-foreground transition hover:bg-secondary hover:text-foreground focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition.opacity.duration.200ms class="absolute right-0 top-full z-10 mt-1 min-w-[160px] overflow-hidden rounded-[1.2rem] border border-border bg-popover p-1.5 shadow-[0_12px_24px_-10px_rgba(15,23,42,0.2)]">
                                <a href="{{ route('customers.show', $customer->uuid) }}" data-modal-open class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M2.06 12.35a1 1 0 0 1 0-.7C3.42 8.6 6.47 6 12 6s8.58 2.6 9.94 5.65a1 1 0 0 1 0 .7C20.58 15.4 17.53 18 12 18s-8.58-2.6-9.94-5.65Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    View Profile
                                </a>
                                @if(auth()->user()?->hasPermission('customers.update'))
                                    <a href="{{ route('customers.edit', $customer->uuid) }}" data-modal-open class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M12 20h9"/><path d="m16.5 3.5 4 4L7 21H3v-4z"/>
                                        </svg>
                                        Edit Customer
                                    </a>
                                @endif
                                @if(auth()->user()?->hasPermission('customers.delete'))
                                    <div class="my-1 border-t border-border/50"></div>
                                    @if($customer->trashed())
                                        <form action="{{ route('customers.restore', $customer->uuid) }}" method="POST" onsubmit="return confirm('Restore this customer?')" class="block">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-emerald-600 transition hover:bg-emerald-500/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                                </svg>
                                                Restore Profile
                                            </button>
                                        </form>
                                        <form action="{{ route('customers.bulk-action') }}" method="POST" onsubmit="return confirm('PERMANENTLY DELETE this customer profile? This action is irreversible.')" class="block">
                                            @csrf
                                            <input type="hidden" name="action" value="force-delete">
                                            <input type="hidden" name="ids[]" value="{{ $customer->uuid }}">
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M10 11v6M14 11v6"/>
                                                </svg>
                                                Force Delete
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('customers.destroy', $customer->uuid) }}" method="POST" onsubmit="return confirm('Archive this customer?')" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/>
                                                </svg>
                                                Archive
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
                        <div class="empty-state">No customers matched the current filters.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="text-sm text-muted-foreground">
        Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} records.
        Page {{ $customers->currentPage() }} of {{ $customers->lastPage() }}.
    </div>
    <div>{{ $customers->links() }}</div>
</div>
</div>
