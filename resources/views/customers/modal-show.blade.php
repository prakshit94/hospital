<div class="p-5 sm:p-6 lg:p-7 max-h-[85vh] overflow-y-auto">
    {{-- Header Section --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div class="flex-1">
            <div class="flex flex-wrap gap-2 items-center mb-2">
                <span class="{{ $customer->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }} font-black uppercase text-[10px] tracking-tight px-2 py-0.5 rounded-lg border border-current/20">
                    {{ $customer->status }} customer
                </span>
                <span class="badge-secondary text-[10px] font-black uppercase tracking-widest px-2 py-0.5">
                    {{ $customer->customer_code }}
                </span>
                @if($customer->is_blacklisted)
                    <span class="bg-red-500/10 text-red-600 font-black uppercase text-[10px] tracking-tight px-2 py-0.5 rounded-lg border border-red-600/20">
                        Blacklisted
                    </span>
                @endif
            </div>
            <h2 class="font-heading text-3xl font-black tracking-tight text-foreground leading-tight">
                {{ $customer->display_name }}
            </h2>
            <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-muted-foreground font-medium">
                <span class="flex items-center gap-1.5">
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $customer->mobile }}
                </span>
                @if($customer->email)
                    <span class="flex items-center gap-1.5 line-clamp-1">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $customer->email }}
                    </span>
                @endif
            </div>
        </div>
        <button type="button" data-modal-close class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-border bg-secondary/50 text-muted-foreground transition hover:bg-secondary hover:text-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <div class="grid gap-8 sm:grid-cols-2">
        {{-- Left Column: Core Details --}}
        <div class="space-y-8">
            {{-- Contact Information --}}
            <section>
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-4 flex items-center gap-2">
                    <span class="h-px flex-1 bg-border/60"></span>
                    Contact Channels
                    <span class="h-px flex-1 bg-border/60"></span>
                </h3>
                <div class="grid gap-4">
                    <div class="flex items-center justify-between p-3 rounded-xl border border-border/50 bg-secondary/20">
                        <span class="text-xs font-bold text-muted-foreground">WhatsApp</span>
                        <span class="text-xs font-black text-foreground">{{ $customer->whatsapp_number ?? ($customer->mobile ?: 'N/A') }}</span>
                    </div>
                    @if($customer->phone_number_2)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-border/50 bg-secondary/20">
                            <span class="text-xs font-bold text-muted-foreground">Backup Phone</span>
                            <span class="text-xs font-black text-foreground">{{ $customer->phone_number_2 }}</span>
                        </div>
                    @endif
                    @if($customer->alternate_email)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-border/50 bg-secondary/20">
                            <span class="text-xs font-bold text-muted-foreground">Alt Email</span>
                            <span class="text-xs font-black text-foreground">{{ $customer->alternate_email }}</span>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Business Details --}}
            @if($customer->category === 'business')
                <section>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-4 flex items-center gap-2">
                        <span class="h-px flex-1 bg-primary/20"></span>
                        Corporate Profile
                        <span class="h-px flex-1 bg-primary/20"></span>
                    </h3>
                    <div class="p-4 rounded-2xl border border-primary/20 bg-primary/5 space-y-3">
                        <div>
                            <div class="text-[9px] font-black uppercase tracking-widest text-primary/60 mb-0.5">Company Name</div>
                            <div class="text-sm font-black text-foreground">{{ $customer->company_name }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-[9px] font-black uppercase tracking-widest text-primary/60 mb-0.5">GSTIN</div>
                                <div class="text-xs font-bold text-foreground">{{ $customer->gst_number ?: '---' }}</div>
                            </div>
                            <div>
                                <div class="text-[9px] font-black uppercase tracking-widest text-primary/60 mb-0.5">PAN</div>
                                <div class="text-xs font-bold text-foreground">{{ $customer->pan_number ?: '---' }}</div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            {{-- Classification --}}
            <section>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 rounded-xl border border-border/50 bg-secondary/10">
                        <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-1">Type</div>
                        <div class="text-xs font-bold text-foreground">{{ ucfirst($customer->type) }}</div>
                    </div>
                    <div class="p-3 rounded-xl border border-border/50 bg-secondary/10">
                        <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-1">Group</div>
                        <div class="text-xs font-bold text-foreground">{{ $customer->customer_group ?: 'General' }}</div>
                    </div>
                    <div class="p-3 rounded-xl border border-border/50 bg-secondary/10">
                        <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-1">Source</div>
                        <div class="text-xs font-bold text-foreground">{{ $customer->source ?: 'Organic' }}</div>
                    </div>
                    <div class="p-3 rounded-xl border border-border/50 bg-secondary/10">
                        <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-1">Lead Status</div>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-tight {{ $customer->lead_status == 'converted' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-amber-500/10 text-amber-600' }}">
                            {{ $customer->lead_status }}
                        </span>
                    </div>
                </div>
            </section>
        </div>

        {{-- Right Column: Agricultural & Financial --}}
        <div class="space-y-8">
            {{-- Agriculture Profile --}}
            <section>
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-4 flex items-center gap-2">
                    <span class="h-px flex-1 bg-border/60"></span>
                    Agricultural Profile
                    <span class="h-px flex-1 bg-border/60"></span>
                </h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="p-3 rounded-xl border border-border/50 bg-secondary/20">
                        <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-1">Land Size</div>
                        <div class="text-xs font-black text-foreground">{{ $customer->land_area ?: 0 }} {{ ucfirst($customer->land_unit) }}s</div>
                    </div>
                    <div class="p-3 rounded-xl border border-border/50 bg-secondary/20">
                        <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-1">Irrigation</div>
                        <div class="text-xs font-black text-foreground">{{ $customer->irrigation_type ?: 'Manual' }}</div>
                    </div>
                </div>
                <div class="p-4 rounded-2xl border border-border/50 bg-secondary/10">
                    <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-2">Cultivated Crops</div>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($customer->crops ?? [] as $crop)
                            <span class="ui-chip text-[9px] font-black italic uppercase tracking-tighter">{{ $crop }}</span>
                        @empty
                            <span class="text-[10px] text-muted-foreground italic">No crop data recorded</span>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- Financial Health --}}
            <section>
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-4 flex items-center gap-2">
                    <span class="h-px flex-1 bg-border/60"></span>
                    Financial Health
                    <span class="h-px flex-1 bg-border/60"></span>
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-danger/5 border border-danger/20">
                        <span class="text-xs font-bold text-danger">Outstanding</span>
                        <span class="text-sm font-black text-danger">₹{{ number_format($customer->outstanding_balance, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-foreground/5 border border-border/50">
                        <span class="text-xs font-bold text-muted-foreground">Credit Limit</span>
                        <span class="text-sm font-black text-foreground">₹{{ number_format($customer->credit_limit, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-foreground/5 border border-border/50">
                        <span class="text-xs font-bold text-muted-foreground">Aadhaar (Last 4)</span>
                        <span class="text-xs font-black text-foreground">{{ $customer->aadhaar_last4 ?: 'Not Provided' }}</span>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Bottom Notes Section --}}
    @if($customer->internal_notes)
        <div class="mt-8 p-4 rounded-2xl bg-amber-500/5 border border-amber-500/20">
            <div class="text-[9px] font-black uppercase tracking-[0.2em] text-amber-600 mb-2 leading-none">Internal Administrative Notes</div>
            <div class="text-xs text-amber-900/80 leading-relaxed font-medium italic">
                "{{ $customer->internal_notes }}"
            </div>
        </div>
    @endif

    {{-- Footer Actions --}}
    <div class="mt-10 flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-border/60">
        <x-ui.button variant="secondary" data-modal-close class="w-full sm:w-auto">Close View</x-ui.button>
        <x-ui.button href="{{ route('customers.edit', $customer->uuid) }}" data-modal-open class="w-full sm:w-auto">
            Edit Full Profile
        </x-ui.button>
        <x-ui.button href="{{ route('customers.show', $customer->uuid) }}" class="w-full sm:w-auto">
            View 360° Profile
        </x-ui.button>
    </div>
</div>
