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
    <table class="w-full">
        <thead class="bg-secondary/30">
            <tr>
                <th class="w-12 p-4 text-left">
                    <input type="checkbox" class="ui-checkbox" x-model="selectAll" @change="toggleAll">
                </th>
                <th class="w-12 font-black text-[10px] uppercase tracking-widest text-muted-foreground p-4 text-center">#</th>
                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground text-left">Identity & Lifecycle</th>
                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground text-left">Connectivity</th>
                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground text-left">Location & Agri</th>
                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground text-center">Status</th>
                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground text-right pr-6">Financial Ledger</th>
                <th class="p-4 text-right pr-6 w-16"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border/40">
            @forelse($customers as $customer)
                <tr class="group hover:bg-secondary/10 transition-all duration-300 {{ $customer->trashed() ? 'opacity-60 grayscale-[0.3]' : '' }}">
                    <td class="p-4">
                        <input type="checkbox" class="ui-checkbox row-checkbox" value="{{ $customer->uuid }}" x-model="selected" @change="selectAll = selected.length === allIds().length">
                    </td>
                    <td class="p-4 font-mono text-[10px] font-bold text-muted-foreground/60 text-center">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                    <td class="p-4" data-label="Identity">
                        <div class="flex items-center gap-3.5">
                            <div class="relative shrink-0">
                                <div class="flex h-11 w-11 items-center justify-center rounded-[1rem] bg-primary/10 text-primary font-black shadow-sm text-sm border border-primary/20">
                                    {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name ?: ($customer->middle_name ?: ''), 0, 1) }}
                                </div>
                                @if($customer->is_blacklisted)
                                    <span class="absolute -right-1 -top-1 block size-4 rounded-full bg-rose-500 border-2 border-card text-[8px] flex items-center justify-center text-white shadow-lg shadow-rose-500/20">!</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-heading text-sm font-black text-foreground hover:text-primary transition-colors cursor-default whitespace-nowrap overflow-hidden text-ellipsis">{{ $customer->display_name }}</div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">{{ $customer->customer_code }}</span>
                                    <span class="size-1 rounded-full bg-border"></span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-primary/70">{{ $customer->type }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4" data-label="Connectivity">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-black text-foreground/90 tabular-nums">{{ $customer->mobile }}</span>
                                @if($customer->whatsapp_number)
                                    <svg class="size-3.5 text-emerald-500 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                @endif
                            </div>
                            <div class="text-[10px] font-black text-muted-foreground/60 lowercase tracking-widest break-all line-clamp-1 max-w-[140px]">{{ $customer->email ?: 'N/A' }}</div>
                        </div>
                    </td>
                    <td class="p-4" data-label="Location & Agri">
                        <div class="space-y-1">
                            <div class="text-xs font-black text-foreground/80 flex items-center gap-1.5">
                                <svg class="size-3 text-muted-foreground/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $customer->primaryAddress?->village?->village_name ?: 'Global' }}
                            </div>
                            @if($customer->crops && count($customer->crops) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($customer->crops, 0, 2) as $crop)
                                        <span class="text-[9px] font-black italic uppercase tracking-tighter text-emerald-600/70">{{ $crop }}</span>
                                    @endforeach
                                    @if(count($customer->crops) > 2)
                                        <span class="text-[9px] font-black text-muted-foreground/40">+{{ count($customer->crops) - 2 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="p-4 text-center" data-label="Status">
                        @if($customer->trashed())
                            <span class="bg-rose-500/10 text-rose-600 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-rose-500/20">Archived</span>
                        @else
                            <div class="flex justify-center">
                                <x-ui.toggle
                                    :active="$customer->status === 'active'"
                                    :action="route('customers.toggle-status', $customer->uuid)"
                                />
                            </div>
                        @endif
                    </td>
                    <td class="p-4 text-right pr-6" data-label="Financial">
                        <div class="flex flex-col items-end">
                            <div class="text-sm font-black {{ $customer->outstanding_balance > 0 ? 'text-rose-600' : 'text-emerald-600' }} tabular-nums">
                                ₹{{ number_format($customer->outstanding_balance, 2) }}
                            </div>
                            <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground/60 mt-0.5">
                                Limit: ₹{{ number_format($customer->credit_limit, 2) }}
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-right pr-6" data-label="Actions">
                        <div class="relative flex justify-end" x-data="{ open: false }">
                            <button type="button" @click="open = !open" @click.away="open = false" class="size-9 flex items-center justify-center rounded-xl text-muted-foreground hover:bg-secondary hover:text-primary transition-all active:scale-95 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 transition-transform group-hover:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
    <div class="text-sm text-muted-foreground font-medium">
        Showing <span class="font-bold text-foreground">{{ $customers->firstItem() ?? 0 }}</span> to <span class="font-bold text-foreground">{{ $customers->lastItem() ?? 0 }}</span> of <span class="font-bold text-foreground">{{ $customers->total() }}</span> global records.
    </div>
    <div>{{ $customers->links() }}</div>
</div>
</div>
