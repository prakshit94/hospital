@php
    $customer = $customer ?? null;
    $modalMode = $modalMode ?? false;
    $isEdit = isset($customer) && $customer->exists;
    $categoryValue = old('category', $customer?->category ?? 'individual');
@endphp

<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type="number"] { -moz-appearance: textfield; appearance: textfield; }
</style>

<div class="premium-form space-y-6" x-data="{ 
        category: @js($categoryValue),
        mobile: @js(old('mobile', $customer?->mobile ?? '')),
        is_whatsapp: @js(old('whatsapp_number', $customer?->whatsapp_number ?? '') !== '' && old('whatsapp_number', $customer?->whatsapp_number ?? '') === old('mobile', $customer?->mobile ?? ''))
    }">
    {{-- Identity Row: Core Names --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="ui-field">
            <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">First
                Name *</label>
            <input name="first_name" type="text" value="{{ old('first_name', $customer?->first_name ?? '') }}" required
                class="ui-input h-10 border-border/60 focus:bg-primary/5 transition-all">
        </div>
        <div class="ui-field">
            <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">Middle
                Name</label>
            <input name="middle_name" type="text" value="{{ old('middle_name', $customer?->middle_name ?? '') }}"
                class="ui-input h-10 border-border/60 focus:bg-primary/5 transition-all">
        </div>
        <div class="ui-field">
            <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">Last
                Name</label>
            <input name="last_name" type="text" value="{{ old('last_name', $customer?->last_name ?? '') }}"
                class="ui-input h-10 border-border/60 focus:bg-primary/5 transition-all">
        </div>
    </div>

    {{-- Communication Hub: Simplified & Focused --}}
    <div class="p-5 rounded-2xl bg-secondary/20 border border-border/40 backdrop-blur-sm space-y-5">
        <div
            class="text-[9px] font-black uppercase text-muted-foreground tracking-[0.2em] mb-2 flex items-center gap-3">
            Communication Hub <span class="h-px flex-1 bg-border/40"></span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="ui-field lg:col-span-1">
                <div class="flex items-center justify-between mb-1">
                    <label class="ui-label text-[10px] font-bold uppercase tracking-tight">Primary Mobile *</label>
                    <label class="flex items-center gap-1.5 cursor-pointer group">
                        <input type="checkbox" x-model="is_whatsapp"
                            class="size-3 rounded border-border text-primary focus:ring-primary/20 transition-all">
                        <span
                            class="text-[9px] font-black uppercase tracking-tighter text-muted-foreground group-hover:text-primary transition-colors">WhatsApp</span>
                    </label>
                </div>
                <input name="mobile" x-model="mobile" type="text" required
                    class="ui-input h-10 bg-background/50 text-sm font-semibold">
                {{-- Hidden WhatsApp Logic --}}
                <input type="hidden" name="whatsapp_number" :value="is_whatsapp ? mobile : ''">
            </div>

            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold uppercase tracking-tight mb-1">Alternate Phone</label>
                <input name="phone_number_2" type="text"
                    value="{{ old('phone_number_2', $customer?->phone_number_2 ?? '') }}" placeholder="Backup number"
                    class="ui-input h-10 bg-background/50 text-sm">
            </div>

            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold uppercase tracking-tight mb-1">Relative / Emergency</label>
                <input name="relative_phone" type="text"
                    value="{{ old('relative_phone', $customer?->relative_phone ?? '') }}" placeholder="Family contact"
                    class="ui-input h-10 bg-background/50 text-sm">
            </div>

            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold uppercase tracking-tight mb-1">Primary Email</label>
                <input name="email" type="email" value="{{ old('email', $customer?->email ?? '') }}"
                    placeholder="example@mail.com" class="ui-input h-10 bg-background/50 text-sm">
            </div>
        </div>
    </div>

    {{-- Classification & KYC --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="p-5 rounded-2xl border border-border/50 bg-secondary/10 space-y-4">
            <div
                class="text-[9px] font-black uppercase text-muted-foreground tracking-[0.2em] mb-1 flex items-center gap-2">
                Classification <span class="h-px flex-1 bg-border/40"></span>
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold">Category</label>
                <select name="category" x-model="category" class="ui-select h-10 py-1">
                    <option value="individual">Individual</option>
                    <option value="business">Business</option>
                </select>
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold">Account Type</label>
                <select name="type" class="ui-select h-10 py-1">
                    @foreach(['farmer', 'buyer', 'vendor', 'dealer'] as $val)
                        <option value="{{ $val }}" @selected(old('type', $customer?->type ?? 'farmer') == $val)>
                            {{ ucfirst($val) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="p-5 rounded-2xl border border-border/50 bg-secondary/10 space-y-4">
            <div
                class="text-[9px] font-black uppercase text-muted-foreground tracking-[0.2em] mb-1 flex items-center gap-2">
                CRM & Logistics <span class="h-px flex-1 bg-border/40"></span>
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold">Lead Status</label>
                <select name="lead_status" class="ui-select h-10 py-1">
                    <option value="lead">Lead</option>
                    <option value="converted">Converted</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold">Group / Tag</label>
                <input name="customer_group" type="text"
                    value="{{ old('customer_group', $customer?->customer_group ?? '') }}" class="ui-input h-10">
            </div>
        </div>

        <div class="p-5 rounded-2xl border border-border/50 bg-secondary/10 space-y-4">
            <div
                class="text-[9px] font-black uppercase text-muted-foreground tracking-[0.2em] mb-1 flex items-center gap-2">
                Intelligence <span class="h-px flex-1 bg-border/40"></span>
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold">Acquisition Source</label>
                <input name="source" type="text" value="{{ old('source', $customer?->source ?? '') }}"
                    class="ui-input h-10">
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold">Aadhaar (Last 4)</label>
                <input name="aadhaar_last4" type="text" maxlength="4"
                    value="{{ old('aadhaar_last4', $customer?->aadhaar_last4 ?? '') }}" class="ui-input h-10">
            </div>
        </div>
    </div>

    {{-- Business Details (Conditional) --}}
    <div x-show="category === 'business'" x-cloak x-transition
        class="p-6 rounded-2xl bg-primary/5 border-2 border-primary/20 space-y-5">
        <div class="flex items-center gap-3">
            <span class="text-primary"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg></span>
            <div class="text-[10px] font-black uppercase text-primary tracking-[0.2em]">Detailed Corporate Profile</div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="ui-field sm:col-span-1">
                <label class="ui-label text-[10px] font-black text-primary/70">Legal Company Name *</label>
                <input name="company_name" type="text" value="{{ old('company_name', $customer?->company_name ?? '') }}"
                    class="ui-input h-10 border-primary/20 bg-background/40">
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-black text-primary/70">GST Number</label>
                <input name="gst_number" type="text" value="{{ old('gst_number', $customer?->gst_number ?? '') }}"
                    class="ui-input h-10 border-primary/20 bg-background/40">
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-black text-primary/70">PAN Identifier</label>
                <input name="pan_number" type="text" value="{{ old('pan_number', $customer?->pan_number ?? '') }}"
                    class="ui-input h-10 border-primary/20 bg-background/40">
            </div>
        </div>
    </div>

    {{-- Agriculture & Financials (Merged) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="p-5 rounded-2xl border border-emerald-500/20 bg-emerald-500/5 space-y-4">
            <div class="text-[9px] font-black uppercase text-emerald-600 tracking-[0.2em] mb-1 flex items-center gap-2">
                Agriculture Metadata <span class="h-px flex-1 bg-emerald-500/20"></span>
            </div>
            <div class="grid grid-cols-1 gap-5">
                <div class="ui-field">
                    <label
                        class="ui-label text-[10px] font-black uppercase tracking-widest text-emerald-800/60 mb-2">Land
                        Assets & Metrics</label>
                    <div
                        class="flex items-stretch group shadow-sm rounded-xl overflow-hidden border border-emerald-500/20 focus-within:border-emerald-500/40 transition-all">
                        <div
                            class="bg-emerald-500/10 px-3.5 flex items-center justify-center text-emerald-600 border-r border-emerald-500/20">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <input name="land_area" type="number" step="0.01"
                            value="{{ old('land_area', $customer?->land_area ?? '') }}"
                            class="ui-input flex-1 w-full h-11 border-0 bg-white focus:ring-0 text-base font-black text-emerald-900 placeholder:text-muted-foreground/30 min-w-[80px]"
                            placeholder="0.00">
                        <select name="land_unit"
                            class="ui-select w-24 h-11 border-0 border-l border-emerald-500/10 bg-emerald-500/5 text-[10px] font-black uppercase tracking-widest text-emerald-800 focus:ring-0">
                            @foreach(['acre', 'bigha', 'hectare', 'kanal', 'marla'] as $unit)
                                <option value="{{ $unit }}" @selected(old('land_unit', $customer?->land_unit ?? 'acre') == $unit)>{{ strtoupper($unit) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="ui-field" x-data="{
                    open: false,
                    search: '',
                    selected: @js(array_filter(explode(',', old('irrigation_type', is_array($customer?->irrigation_type) ? implode(',', $customer->irrigation_type) : ($customer?->irrigation_type ?? ''))))),
                    options: ['Canal', 'Borewell/Tubewell', 'Drip Irrigation', 'Sprinkler System', 'Rainfed (Monsoon)', 'River Intake', 'Check Dams/Tanks'],
                    get filteredOptions() {
                        if (this.search === '') return this.options.filter(opt => !this.selected.includes(opt));
                        return this.options.filter(opt => opt.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(opt));
                    },
                    toggle(opt) {
                        if (this.selected.includes(opt)) {
                            this.selected = this.selected.filter(i => i !== opt);
                        } else {
                            this.selected.push(opt);
                            this.search = '';
                            this.open = false;
                        }
                    }
                }">
                    <label
                        class="ui-label text-[10px] font-black uppercase tracking-widest text-emerald-800/60 mb-2">Irrigation
                        Portfolio</label>
                    <div class="relative">
                        <div @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
                            class="ui-input min-h-[44px] h-auto py-1.5 px-3 border-emerald-500/20 bg-background/40 flex flex-wrap gap-1.5 items-center cursor-text transition-all focus-within:border-emerald-500/40">
                            <template x-for="item in selected" :key="item">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-[10px] font-black text-emerald-700 uppercase tracking-tight">
                                    <span x-text="item"></span>
                                    <button type="button" @click.stop="toggle(item)"
                                        class="hover:text-rose-500 transition-colors">
                                        <svg class="size-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                            <input x-ref="searchInput" x-model="search"
                                @keydown.backspace="if(search === '') selected.pop()" placeholder="Select support..."
                                class="flex-1 bg-transparent border-0 focus:ring-0 p-0 text-sm placeholder:text-muted-foreground/30 min-w-[80px]">
                        </div>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute z-50 w-full mt-2 bg-popover border border-emerald-500/20 rounded-2xl shadow-2xl overflow-hidden max-h-48 overflow-y-auto backdrop-blur-xl">
                            <template x-for="opt in filteredOptions" :key="opt">
                                <div @click="toggle(opt)"
                                    class="px-4 py-2 text-xs font-bold text-foreground hover:bg-emerald-500/10 cursor-pointer transition-colors border-b border-border/10 last:border-0"
                                    x-text="opt"></div>
                            </template>
                            <div x-show="filteredOptions.length === 0"
                                class="px-4 py-3 text-[10px] font-black uppercase italic text-muted-foreground">No
                                matches</div>
                        </div>
                        <template x-for="item in selected" :key="'val-'+item">
                            <input type="hidden" name="irrigation_type[]" :value="item">
                        </template>
                        <input type="hidden" name="irrigation_types_input" :value="selected.join(', ')">
                    </div>
                </div>
            </div>
            <div class="ui-field" x-data="{
                open: false,
                search: '',
                selected: @js(old('crops', $customer?->crops ?? [])),
                options: ['Wheat', 'Cotton', 'Mustard', 'Bajra', 'Soybean', 'Maize', 'Sugarcane', 'Rice/Paddy', 'Potato', 'Onion', 'Garlic', 'Groundnut', 'Chickpea', 'Cumin', 'Cluster Bean'],
                get filteredOptions() {
                    if (this.search === '') return this.options.filter(opt => !this.selected.includes(opt));
                    return this.options.filter(opt => 
                        opt.toLowerCase().includes(this.search.toLowerCase()) && 
                        !this.selected.includes(opt)
                    );
                },
                toggle(opt) {
                    if (this.selected.includes(opt)) {
                        this.selected = this.selected.filter(i => i !== opt);
                    } else {
                        this.selected.push(opt);
                        this.search = '';
                    }
                }
            }">
                <label class="ui-label text-[10px] font-bold">Cultivated Crops Portfolio</label>

                {{-- Multi-Select Container --}}
                <div class="relative mt-1">
                    <div class="ui-input min-h-[44px] h-auto py-2 px-3 flex flex-wrap gap-2 border-emerald-500/20 bg-background/40 cursor-text"
                        @click="open = true; $nextTick(() => $refs.searchInput.focus())">

                        {{-- Selected Chips --}}
                        <template x-for="crop in selected" :key="crop">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 rounded-lg text-[10px] font-black uppercase tracking-tight">
                                <span x-text="crop"></span>
                                <button type="button" @click.stop="toggle(crop)"
                                    class="hover:text-rose-600 transition-colors">
                                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        {{-- Search Input (Embedded) --}}
                        <input x-ref="searchInput" type="text" x-model="search" @keydown.escape="open = false"
                            @keydown.backspace="if (search === '' && selected.length > 0) toggle(selected[selected.length - 1])"
                            class="flex-1 bg-transparent border-0 outline-none p-0 text-xs min-w-[100px] focus:ring-0"
                            placeholder="Search or add crops...">
                    </div>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" x-cloak @click.outside="open = false"
                        class="absolute z-50 mt-2 w-full max-h-60 overflow-y-auto bg-background border border-border/60 rounded-2xl shadow-2xl shadow-emerald-500/10 backdrop-blur-sm p-2 premium-scrollbar"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0">

                        <div class="grid grid-cols-2 gap-1">
                            <template x-for="opt in filteredOptions" :key="opt">
                                <button type="button" @click="toggle(opt)"
                                    class="text-left px-3 py-2 rounded-xl text-xs font-semibold text-muted-foreground hover:bg-emerald-500/5 hover:text-emerald-700 transition-all flex items-center justify-between group">
                                    <span x-text="opt"></span>
                                    <svg class="size-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </template>
                        </div>

                        {{-- Empty State --}}
                        <div x-show="filteredOptions.length === 0" class="p-4 text-center">
                            <p class="text-[10px] text-muted-foreground italic font-medium">No matches found for "<span
                                    x-text="search"></span>"</p>
                        </div>
                    </div>
                </div>

                {{-- Hidden Inputs for Backend Form Submission --}}
                <template x-for="crop in selected" :key="'val-'+crop">
                    <input type="hidden" name="crops[]" :value="crop">
                </template>
                {{-- Legacy fallback for any sync-based logic --}}
                <input type="hidden" name="crops_input" :value="selected.join(', ')">
            </div>
        </div>

        <div class="p-5 rounded-2xl border border-amber-500/20 bg-amber-500/5 space-y-4">
            <div class="text-[9px] font-black uppercase text-amber-600 tracking-[0.2em] mb-1 flex items-center gap-2">
                Financial Guardrails <span class="h-px flex-1 bg-amber-500/20"></span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="ui-field">
                    <label class="ui-label text-[10px] font-bold">Credit Limit (₹)</label>
                    <input name="credit_limit" type="number"
                        value="{{ old('credit_limit', $customer?->credit_limit ?? 0) }}"
                        class="ui-input h-10 border-amber-500/20 bg-background/40">
                </div>
                <div class="ui-field">
                    <label class="ui-label text-[10px] font-bold">Payment Terms (Days)</label>
                    <input name="payment_terms_days" type="number"
                        value="{{ old('payment_terms_days', $customer?->payment_terms_days ?? 0) }}"
                        class="ui-input h-10 border-amber-500/20 bg-background/40">
                </div>
            </div>
            <div class="ui-field">
                <label class="ui-label text-[10px] font-bold">Internal Administrative Notes</label>
                <textarea name="internal_notes"
                    class="ui-input p-3 text-xs h-10 border-amber-500/20 bg-background/40 resize-none min-h-[40px]"
                    placeholder="Add private context...">{{ old('internal_notes', $customer?->internal_notes ?? '') }}</textarea>
            </div>
        </div>
    </div>

@if($modalMode)
    <div class="flex items-center justify-end gap-3 pt-6 mt-8 border-t border-border/40">
        <button type="button" data-modal-close
            class="px-5 py-2 text-xs font-black uppercase tracking-widest text-muted-foreground hover:text-foreground transition-all">Cancel</button>
        <x-ui.button type="submit" class="px-8 py-2 text-xs shadow-xl shadow-primary/20">
            {{ $isEdit ? 'Sync Profile' : 'Register Customer' }}
        </x-ui.button>
    </div>
@else
    <div class="pt-8 border-t border-border/40">
        <x-ui.button type="submit"
            class="w-full h-12 text-sm font-black uppercase tracking-[0.2em] shadow-2xl shadow-primary/30">
            {{ $isEdit ? 'Save Master Account' : 'Commit New Account' }}
        </x-ui.button>
    </div>
@endif