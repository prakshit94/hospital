<div data-record-data="{{ $records->mapWithKeys(fn($r) => [$r->id => $r->trashed()])->toJson() }}" x-data="{
    selected: [],
    selectAll: false,
    recordData: {},
    init() {
        this.recordData = JSON.parse(this.$el.dataset.recordData || '{}');
    },
    allIds() {
        return Object.keys(this.recordData);
    },
    toggleAll() {
        this.selected = this.selectAll ? this.allIds() : [];
    },
    isAnyDeleted() {
        return this.selected.some(id => this.recordData[id]);
    },
    bulkAction(action, formType = 'medical_report') {
        if (this.selected.length === 0) return;
        
        if (action === 'print') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('health-records.bulk-action') }}';
            form.target = '_blank';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'print';
            form.appendChild(actionInput);

            const typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'form_type';
            typeInput.value = formType;
            form.appendChild(typeInput);
            
            this.selected.forEach(id => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'ids[]';
                idInput.value = id;
                form.appendChild(idInput);
            });
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            return;
        }

        let confirmMsg = 'Are you sure?';
        if (action === 'delete') confirmMsg = 'Move selected records to trash?';
        if (action === 'force-delete') confirmMsg = 'CRITICAL: PERMANENTLY delete selected records?';
        if (action === 'restore') confirmMsg = 'Restore selected records?';
        
        if (['delete', 'force-delete', 'restore'].includes(action) && !confirm(confirmMsg)) return;

        fetch('{{ route('health-records.bulk-action') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ action: action, ids: this.selected })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'success', title: 'Success', message: data.message }}));
                const searchForm = document.querySelector('form[data-async-search]');
                if (searchForm) {
                    searchForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                } else {
                    window.location.reload();
                }
            } else {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'error', title: 'Error', message: data.message }}));
            }
            this.selected = [];
            this.selectAll = false;
        });
    }
}">
<div class="table-toolbar">
    <div class="table-toolbar-copy" x-show="selected.length === 0">
        <div class="table-toolbar-title">Health Directory</div>
        <p>Review employee health vitals, BMI, and certificate status.</p>
    </div>

    <!-- Bulk actions ribbon -->
    <div class="flex items-center gap-4 bg-primary/10 backdrop-blur-md py-2.5 px-5 rounded-2xl border border-primary/20 w-full shadow-lg shadow-primary/5" style="display: none;" x-show="selected.length > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="flex items-center gap-2">
            <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-primary text-[10px] font-black text-white shadow-sm" x-text="selected.length"></span>
            <span class="text-xs font-bold uppercase tracking-widest text-primary">Selected</span>
        </div>
        <div class="h-6 w-px bg-primary/20"></div>
        <div class="flex flex-1 items-center gap-2">
            <div class="relative flex" x-data="{ openPrint: false }">
                <button type="button" @click="openPrint = !openPrint" @click.away="openPrint = false" class="inline-flex items-center gap-2 rounded-xl bg-card px-3.5 py-2 text-[10px] font-black uppercase tracking-widest text-foreground shadow-sm border border-border hover:bg-secondary transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    Print
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="openPrint" x-cloak x-transition.opacity.duration.200ms class="absolute left-0 top-full z-20 mt-2 min-w-[220px] overflow-hidden rounded-2xl border border-border bg-popover p-1.5 shadow-xl">
                    <button type="button" @click="bulkAction('print', 'medical_report'); openPrint = false" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wider text-foreground transition hover:bg-secondary hover:text-primary">Medical Report</button>
                    <button type="button" @click="bulkAction('print', 'form32'); openPrint = false" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wider text-foreground transition hover:bg-secondary hover:text-primary">Form 32 (Register)</button>
                    <button type="button" @click="bulkAction('print', 'form33'); openPrint = false" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wider text-foreground transition hover:bg-secondary hover:text-primary">Form 33 (Fitness)</button>
                </div>
            </div>
            
            <div class="h-6 w-px bg-primary/20"></div>

            @if(auth()->user()?->hasPermission('health_records.delete'))
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
        <span class="table-toolbar-stat">{{ $records->total() }} total records</span>
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
                <th>Employee Info</th>
                <th>Company & ID</th>
                <th>Vitals</th>
                <th>BMI</th>
                <th>Docs</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr class="group transition hover:bg-secondary/20 {{ $record->trashed() ? 'opacity-70 grayscale-[0.3]' : '' }}">
                    <td>
                        <input type="checkbox" class="ui-checkbox row-checkbox" value="{{ $record->id }}" x-model="selected" @change="selectAll = selected.length === allIds().length">
                    </td>
                    <td class="table-secondary font-mono text-xs">{{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}</td>
                    <td data-label="Employee Info">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary font-bold shadow-sm">
                                {{ strtoupper(substr($record->full_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="table-primary">{{ $record->full_name }}</div>
                                <div class="mt-0.5 text-xs text-muted-foreground">{{ ucfirst($record->gender) }}, {{ $record->blood_group ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td data-label="Company & ID">
                        <div class="table-primary">{{ $record->company_name }}</div>
                        <div class="mt-0.5 text-xs text-muted-foreground">ID: {{ $record->employee->employee_id ?? 'N/A' }}</div>
                    </td>
                    <td data-label="Vitals">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase text-muted-foreground/60 w-8">BP:</span>
                                <span class="font-medium text-xs">{{ $record->bp_systolic ?? '--' }}/{{ $record->bp_diastolic ?? '--' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase text-muted-foreground/60 w-8">HR:</span>
                                <span class="font-medium text-xs">{{ $record->heart_rate ?? '--' }} bpm</span>
                            </div>
                        </div>
                    </td>
                    <td data-label="BMI">
                        @if($record->bmi)
                            <span class="ui-chip {{ $record->bmi >= 18.5 && $record->bmi <= 24.9 ? '!bg-emerald-500/10 !text-emerald-600' : '!bg-amber-500/10 !text-amber-600' }}">
                                {{ $record->bmi }}
                            </span>
                        @else
                            <span class="ui-chip-muted">N/A</span>
                        @endif
                    </td>
                    <td data-label="Docs">
                        @if(($record->documents_count ?? 0) > 0)
                            <a href="{{ route('health-records.show', $record->uuid) }}#documents"
                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-500/10 text-teal-600 text-xs font-bold transition hover:bg-teal-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                {{ $record->documents_count }}
                            </a>
                        @else
                            <span class="text-xs text-muted-foreground/50">—</span>
                        @endif
                    </td>
                    <td data-label="Status">
                        @if($record->trashed())
                            <span class="ui-status-danger px-2 py-1 uppercase text-[10px] tracking-wide font-bold">Deleted</span>
                        @else
                            <span class="ui-chip !bg-primary/5 !text-primary uppercase text-[10px] tracking-wider font-bold">
                                {{ $record->status }}
                            </span>
                        @endif
                    </td>
                    <td data-label="Actions" class="actions-cell">
                        <div class="relative flex justify-end" x-data="{ open: false }">
                            <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-center rounded-lg p-2 text-muted-foreground transition hover:bg-secondary hover:text-foreground focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition.opacity.duration.200ms class="absolute right-0 top-full z-10 mt-1 min-w-[200px] overflow-hidden rounded-[1.2rem] border border-border bg-popover p-1.5 shadow-[0_12px_24px_-10px_rgba(15,23,42,0.2)]">
                                <a href="{{ route('health-records.show', $record->uuid) }}" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M2.06 12.35a1 1 0 0 1 0-.7C3.42 8.6 6.47 6 12 6s8.58 2.6 9.94 5.65a1 1 0 0 1 0 .7C20.58 15.4 17.53 18 12 18s-8.58-2.6-9.94-5.65Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    View Details
                                </a>

                                @if(!$record->trashed())
                                    <div class="my-1 border-t border-border/50"></div>
                                    <a href="{{ route('health-records.print', $record->uuid) }}" target="_blank" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                                        </svg>
                                        Medical Report
                                    </a>
                                    <a href="{{ route('health-records.print-form32', $record->uuid) }}" target="_blank" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-500/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>
                                        </svg>
                                        Print Form 32
                                    </a>
                                    <a href="{{ route('health-records.print-form33', $record->uuid) }}" target="_blank" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-blue-600 transition hover:bg-blue-500/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                        Print Form 33
                                    </a>
                                    <div class="my-1 border-t border-border/50"></div>
                                    <a href="{{ route('health-records.print-all', $record->uuid) }}" target="_blank" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                                        </svg>
                                        Print Complete Report
                                    </a>
                                    <div class="my-1 border-t border-border/50"></div>
                                    <a href="{{ route('health-records.edit', $record->uuid) }}" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M12 20h9"/><path d="m16.5 3.5 4 4L7 21H3v-4z"/>
                                        </svg>
                                        Edit Record
                                    </a>
                                    @if(auth()->user()?->hasPermission('health_records.delete'))
                                        <form action="{{ route('health-records.destroy', $record->uuid) }}" method="POST" onsubmit="return confirm('Delete this record?')" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/>
                                                </svg>
                                                Delete Record
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    @if(auth()->user()?->hasPermission('health_records.delete'))
                                        <div class="my-1 border-t border-border/50"></div>
                                        <form action="{{ route('health-records.restore', $record->uuid) }}" method="POST" onsubmit="return confirm('Restore this record?')" class="block">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-emerald-600 transition hover:bg-emerald-500/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                                                </svg>
                                                Restore Record
                                            </button>
                                        </form>
                                        <form action="{{ route('health-records.force-delete', $record->uuid) }}" method="POST" onsubmit="return confirm('CRITICAL: Permanently delete this record?')" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-danger transition hover:bg-danger/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/>
                                                </svg>
                                                Permanent Delete
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
                    <td colspan="9">
                        <div class="empty-state">No health records matched the current filters.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="text-sm text-muted-foreground">
        Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} records.
        Page {{ $records->currentPage() }} of {{ $records->lastPage() }}.
    </div>
    <nav class="flex items-center gap-2 pagination">
        @if ($records->onFirstPage())
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </span>
        @else
            <a href="{{ $records->previousPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </a>
        @endif

        @if ($records->hasMorePages())
            <a href="{{ $records->nextPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
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
