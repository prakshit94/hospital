<div class="p-6 sm:p-8 lg:p-10 max-h-[90vh] overflow-y-auto bg-background/95 backdrop-blur-xl">
    {{-- Top Utility Bar --}}
    <div class="flex justify-end mb-4">
        <button type="button" data-modal-close class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-border/60 bg-secondary/30 text-muted-foreground transition-all hover:bg-secondary hover:text-foreground hover:scale-105 active:scale-95 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- Premium Header: High Impact --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 px-1">
        <div class="space-y-4">
            <div class="flex flex-wrap gap-2 items-center">
                <span class="{{ $customer->status === 'active' ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 border-rose-500/20' }} font-black uppercase text-[10px] tracking-[0.2em] px-3 py-1 rounded-full border shadow-sm">
                    {{ $customer->status }} account
                </span>
                <span class="bg-primary/5 text-primary border-primary/20 text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full border shadow-sm">
                    {{ $customer->customer_code }}
                </span>
                @if($customer->is_blacklisted)
                    <span class="bg-rose-500 text-white font-black uppercase text-[10px] tracking-[0.2em] px-3 py-1 rounded-full shadow-lg shadow-rose-500/20">
                        Sanctioned
                    </span>
                @endif
            </div>
            <h2 class="font-heading text-4xl font-black tracking-tight text-foreground leading-[1.1] drop-shadow-sm">
                {{ $customer->display_name }}
            </h2>
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-muted-foreground font-semibold">
                <span class="flex items-center gap-2 hover:text-primary transition-colors cursor-default">
                    <div class="size-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary border border-primary/20 shadow-inner">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    {{ $customer->mobile }}
                </span>
                @if($customer->email)
                    <span class="flex items-center gap-2 hover:text-primary transition-colors cursor-default">
                        <div class="size-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary border border-primary/20 shadow-inner">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        {{ $customer->email }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Core Multi-Matrix: Financials & Classification --}}
    <div class="grid gap-6 mb-10 sm:grid-cols-4">
        <div class="group p-5 rounded-2xl border-2 border-rose-500/20 bg-rose-500/5 hover:border-rose-500/40 transition-all duration-300">
            <div class="text-[10px] font-black uppercase tracking-widest text-rose-600/70 mb-1 leading-none">Net Exposure</div>
            <div class="text-2xl font-black text-rose-600 tabular-nums">₹{{ number_format($customer->outstanding_balance, 2) }}</div>
            <div class="mt-2 h-1 w-full bg-rose-200/50 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500 rounded-full" style="width: 65%"></div>
            </div>
        </div>
        <div class="group p-5 rounded-2xl border border-border/60 bg-secondary/10 hover:bg-secondary/20 transition-all duration-300">
            <div class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 leading-none">Credit Ceiling</div>
            <div class="text-2xl font-black text-foreground tabular-nums">₹{{ number_format($customer->credit_limit, 2) }}</div>
        </div>
        <div class="group p-5 rounded-2xl border border-border/60 bg-secondary/10 hover:bg-secondary/20 transition-all duration-300">
            <div class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 leading-none">Lead Lifecycle</div>
            <div class="flex items-center gap-2 mt-1">
                <div class="size-2 rounded-full animate-pulse {{ $customer->lead_status == 'converted' ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]' }}"></div>
                <span class="text-sm font-black uppercase tracking-widest text-foreground">{{ $customer->lead_status }}</span>
            </div>
        </div>
        <div class="group p-5 rounded-2xl border border-border/60 bg-secondary/10 hover:bg-secondary/20 transition-all duration-300">
            <div class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 leading-none">Account Tiers</div>
            <div class="text-sm font-black uppercase tracking-widest text-foreground mt-1">{{ $customer->type }} / {{ $customer->customer_group ?: 'General' }}</div>
        </div>
    </div>

    {{-- Detailed Intelligence Grid --}}
    <div class="grid gap-8 sm:grid-cols-3">
        {{-- Communication Channels --}}
        <div class="space-y-6">
            <section>
                <div class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 mb-4 flex items-center gap-3">
                    Direct Channels <span class="h-px flex-1 bg-primary/10"></span>
                </div>
                <div class="grid gap-3">
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-border/40 bg-background shadow-sm group hover:border-primary/30 transition-all">
                        <span class="text-xs font-bold text-muted-foreground">WhatsApp</span>
                        <span class="text-sm font-black text-foreground">{{ $customer->whatsapp_number ?: '---' }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-border/40 bg-background shadow-sm hover:border-primary/30 transition-all">
                        <span class="text-xs font-bold text-muted-foreground">Secondary</span>
                        <span class="text-sm font-black text-foreground">{{ $customer->phone_number_2 ?: '---' }}</span>
                    </div>
                </div>
            </section>
        </div>

        {{-- Agriculture Metadata --}}
        <div class="space-y-6">
            <section>
                <div class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 mb-4 flex items-center gap-3">
                    Agri-Intelligence <span class="h-px flex-1 bg-emerald-500/10"></span>
                </div>
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-xl bg-white/60 backdrop-blur-sm border border-emerald-500/20 flex items-center justify-center text-emerald-600 shadow-inner">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-[9px] font-black uppercase tracking-widest text-emerald-800/60 leading-none mb-1">Land Portfolio</div>
                                <div class="text-lg font-black text-emerald-900 leading-none italic uppercase">
                                    {{ $customer->land_area > 0 ? strtoupper($customer->land_unit) : 'UNSPECIFIED' }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-emerald-600 tabular-nums leading-none">
                                {{ $customer->land_area ?: '0.00' }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-[9px] font-bold text-emerald-800/50 uppercase mb-2 flex items-center gap-1.5">
                                <svg class="size-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                Primary Cultivations
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($customer->crops ?? [] as $crop)
                                    <span class="px-2 py-0.5 bg-white/80 backdrop-blur-sm border border-emerald-500/20 rounded-lg text-[9px] font-black italic text-emerald-700 shadow-sm">{{ $crop }}</span>
                                @empty
                                    <span class="text-[10px] italic text-muted-foreground/50">None listed</span>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <div class="text-[9px] font-bold text-emerald-800/50 uppercase mb-2 flex items-center gap-1.5">
                                <svg class="size-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86 1.406l-1.274 1.103a2 2 0 01-1.568.548l-1.769-.146a2 2 0 01-1.564-1.158l-.477-1.334a6 6 0 00-1.158-1.564l-1.334-.477a2 2 0 01-1.158-1.564l-.146-1.769a2 2 0 01.548-1.568l1.103-1.274a6 6 0 001.406-3.86l-.477-2.387a2 2 0 00-.547-1.022L7.43 2.572a11.962 11.962 0 00-5.714 5.714L1.716 10.428z"/></svg>
                                Irrigation Support
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @php
                                    $irrigations = is_array($customer->irrigation_type) ? $customer->irrigation_type : (is_string($customer->irrigation_type) ? array_filter(explode(',', $customer->irrigation_type)) : []);
                                @endphp
                                @forelse($irrigations as $type)
                                    <span class="px-2 py-0.5 bg-emerald-500/5 border border-emerald-500/20 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-tighter">{{ trim($type) }}</span>
                                @empty
                                    <span class="text-[10px] italic text-muted-foreground/50">Not specified</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Business & Security --}}
        <div class="space-y-6">
            @if($customer->category === 'business')
                <section>
                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-4 flex items-center gap-3">
                        Corporate Stack <span class="h-px flex-1 bg-primary/10"></span>
                    </div>
                    <div class="p-4 rounded-2xl border border-primary/20 bg-primary/5 space-y-3 shadow-inner">
                        <div class="text-sm font-black text-foreground line-clamp-1 border-b border-primary/10 pb-2">{{ $customer->company_name }}</div>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div>
                                <div class="text-[9px] font-bold text-primary/50 uppercase">VAT/GST</div>
                                <div class="text-[11px] font-black text-foreground">{{ $customer->gst_number ?: '---' }}</div>
                            </div>
                            <div>
                                <div class="text-[9px] font-bold text-primary/50 uppercase">Tax ID (PAN)</div>
                                <div class="text-[11px] font-black text-foreground">{{ $customer->pan_number ?: '---' }}</div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            <section>
                <div class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-4 flex items-center gap-3">
                    Verification <span class="h-px flex-1 bg-border/40"></span>
                </div>
                <div class="p-3.5 rounded-xl border border-border/40 bg-secondary/10 flex justify-between items-center transition-all hover:bg-secondary/20">
                    <span class="text-xs font-bold text-muted-foreground uppercase flex items-center gap-2">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Aadhaar (L4)
                    </span>
                    <span class="text-sm font-black text-foreground tracking-widest">{{ $customer->aadhaar_last4 ?: '----' }}</span>
                </div>
            </section>
        </div>
    </div>

    {{-- Administrative Context --}}
    @if($customer->internal_notes)
        <div class="mt-10 p-5 rounded-2xl bg-amber-500/5 border border-amber-500/20 shadow-inner relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-2 opacity-20 group-hover:opacity-100 transition-opacity">
                <svg class="size-12 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 13.1216 16 12.017 16C10.9124 16 10.017 16.8954 10.017 18L10.017 21H4V3H20V21H14.017ZM12 11C13.6569 11 15 9.65685 15 8C15 6.34315 13.6569 5 12 5C10.3431 5 9 6.34315 9 8C9 9.65685 10.3431 11 12 11Z"/></svg>
            </div>
            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-700 mb-3 leading-none">Administrative Context</div>
            <div class="text-sm text-amber-900/80 leading-relaxed font-medium italic border-l-2 border-amber-500/40 pl-4 py-1">
                "{{ $customer->internal_notes }}"
            </div>
        </div>
    @endif

    {{-- Premium Footer Actions --}}
    <div class="mt-12 flex flex-col sm:flex-row items-center justify-end gap-4 pt-8 border-t border-border/80">
        <button type="button" data-modal-close class="w-full sm:w-auto px-6 py-2.5 text-[11px] font-black uppercase tracking-widest text-muted-foreground hover:text-foreground transition-all">
            Dismiss View
        </button>
        <x-ui.button href="{{ route('customers.edit', $customer->uuid) }}" data-modal-open class="w-full sm:w-auto px-10 py-3 text-[11px] font-black uppercase tracking-widest shadow-2xl shadow-primary/30">
            Modify Account
        </x-ui.button>
        <x-ui.button href="{{ route('customers.show', $customer->uuid) }}" class="w-full sm:w-auto px-10 py-3 text-[11px] font-black uppercase tracking-widest">
            360° Intelligence
        </x-ui.button>
    </div>
</div>
