@php
    $customer = $customer ?? null;
    $isEdit = $customer?->exists ?? false;
    $modalMode = $modalMode ?? false;

    // ✅ Safe defaults
    $categoryValue = old('category', $customer?->category ?? 'individual');
@endphp

<div 
    class="{{ $modalMode ? 'space-y-6' : 'grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(380px,0.9fr)]' }}"
    x-data="{
        category: @js($categoryValue)
    }"
>

    <!-- Main Content Area -->
    <div class="space-y-6">

        {{-- Section: Identity --}}
        <x-ui.card class="space-y-6">
            <div class="border-b border-border/50 pb-4">
                <h2 class="text-lg font-bold text-foreground">Identity & Profile</h2>
                <p class="text-xs text-muted-foreground">Full name and basic classification.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-3">
                <div class="ui-field">
                    <label class="ui-label">First Name *</label>
                    <input name="first_name" type="text"
                        value="{{ old('first_name', $customer?->first_name ?? '') }}"
                        required
                        class="ui-input @error('first_name') !border-danger ring-2 ring-danger/10 @enderror">
                </div>

                <div class="ui-field">
                    <label class="ui-label">Middle Name</label>
                    <input name="middle_name" type="text"
                        value="{{ old('middle_name', $customer?->middle_name ?? '') }}"
                        class="ui-input">
                </div>

                <div class="ui-field">
                    <label class="ui-label">Last Name</label>
                    <input name="last_name" type="text"
                        value="{{ old('last_name', $customer?->last_name ?? '') }}"
                        class="ui-input">
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="ui-field">
                    <label class="ui-label">Public Display Name</label>
                    <input name="display_name" type="text"
                        value="{{ old('display_name', $customer?->display_name ?? '') }}"
                        placeholder="Leave blank to auto-generate"
                        class="ui-input">
                </div>

                <div class="ui-field">
                    <label class="ui-label">Aadhaar (Last 4)</label>
                    <input name="aadhaar_last4" type="text" maxlength="4"
                        value="{{ old('aadhaar_last4', $customer?->aadhaar_last4 ?? '') }}"
                        class="ui-input">
                </div>
            </div>
        </x-ui.card>

        {{-- Section: Contact Channels --}}
        <x-ui.card class="space-y-6">
            <div class="border-b border-border/50 pb-4">
                <h2 class="text-lg font-bold text-foreground">Contact Channels</h2>
                <p class="text-xs text-muted-foreground">Multiple ways to reach the customer.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="ui-field">
                    <label class="ui-label">Primary Mobile *</label>
                    <input name="mobile" type="text"
                        value="{{ old('mobile', $customer?->mobile ?? '') }}"
                        required
                        class="ui-input @error('mobile') !border-danger ring-2 ring-danger/10 @enderror">
                </div>

                <div class="ui-field">
                    <label class="ui-label">WhatsApp Number</label>
                    <input name="whatsapp_number" type="text"
                        value="{{ old('whatsapp_number', $customer?->whatsapp_number ?? '') }}"
                        placeholder="Same as mobile id empty"
                        class="ui-input">
                </div>

                <div class="ui-field">
                    <label class="ui-label">Primary Email</label>
                    <input name="email" type="email"
                        value="{{ old('email', $customer?->email ?? '') }}"
                        class="ui-input @error('email') !border-danger ring-2 ring-danger/10 @enderror">
                </div>

                <div class="ui-field">
                    <label class="ui-label">Alternate Email</label>
                    <input name="alternate_email" type="email"
                        value="{{ old('alternate_email', $customer?->alternate_email ?? '') }}"
                        class="ui-input">
                </div>

                <div class="ui-field">
                    <label class="ui-label">Backup Phone (2)</label>
                    <input name="phone_number_2" type="text"
                        value="{{ old('phone_number_2', $customer?->phone_number_2 ?? '') }}"
                        class="ui-input">
                </div>

                <div class="ui-field">
                    <label class="ui-label">Relative/Emergency Phone</label>
                    <input name="relative_phone" type="text"
                        value="{{ old('relative_phone', $customer?->relative_phone ?? '') }}"
                        class="ui-input">
                </div>
            </div>
        </x-ui.card>

        {{-- Section: Agriculture --}}
        <x-ui.card class="space-y-6">
            <div class="border-b border-border/50 pb-4">
                <h2 class="text-lg font-bold text-foreground">Agriculture & Business</h2>
                <p class="text-xs text-muted-foreground">Land and farming specifics.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-3">
                <div class="ui-field">
                    <label class="ui-label">Land Area</label>
                    <input name="land_area" type="number" step="0.01"
                        value="{{ old('land_area', $customer?->land_area ?? '') }}"
                        class="ui-input">
                </div>

                <div class="ui-field">
                    <label class="ui-label">Unit</label>
                    <select name="land_unit" class="ui-select">
                        <option value="acre" @selected(old('land_unit', $customer?->land_unit ?? 'acre') === 'acre')>Acre</option>
                        <option value="bigha" @selected(old('land_unit', $customer?->land_unit ?? 'acre') === 'bigha')>Bigha</option>
                        <option value="hectare" @selected(old('land_unit', $customer?->land_unit ?? 'acre') === 'hectare')>Hectare</option>
                    </select>
                </div>

                <div class="ui-field">
                    <label class="ui-label">Irrigation Type</label>
                    <input name="irrigation_type" type="text"
                        value="{{ old('irrigation_type', $customer?->irrigation_type ?? '') }}"
                        placeholder="e.g. Drip, Canal"
                        class="ui-input">
                </div>
            </div>

            <div class="ui-field">
                <label class="ui-label">Crops Table (Comma separated)</label>
                <input name="crops_input" type="text"
                    value="{{ old('crops_input', isset($customer->crops) ? implode(', ', (array) $customer->crops) : '') }}"
                    placeholder="Cotton, Wheat, Soybean..."
                    class="ui-input">
            </div>
        </x-ui.card>

    </div>

    <!-- Sidebar Area -->
    <div class="space-y-6">

        {{-- Classification & Lead --}}
        <x-ui.card class="space-y-5">
            <div class="ui-field">
                <label class="ui-label">Customer Category</label>
                <select name="category" x-model="category" class="ui-select">
                    <option value="individual">Individual</option>
                    <option value="business">Business</option>
                </select>
            </div>

            <div class="ui-field">
                <label class="ui-label">Account Type</label>
                <select name="type" class="ui-select">
                    @foreach(['farmer', 'buyer', 'vendor', 'dealer'] as $val)
                        <option value="{{ $val }}" @selected(old('type', $customer?->type ?? 'farmer') == $val)>
                            {{ ucfirst($val) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="ui-field">
                <label class="ui-label">Lead Status</label>
                <select name="lead_status" class="ui-select">
                    <option value="lead" @selected(old('lead_status', $customer?->lead_status ?? 'lead') === 'lead')>Lead</option>
                    <option value="converted" @selected(old('lead_status', $customer?->lead_status ?? 'lead') === 'converted')>Converted</option>
                    <option value="inactive" @selected(old('lead_status', $customer?->lead_status ?? 'lead') === 'inactive')>Inactive</option>
                </select>
            </div>

            <div class="ui-field">
                <label class="ui-label">Customer Group</label>
                <input name="customer_group" type="text"
                    value="{{ old('customer_group', $customer?->customer_group ?? '') }}"
                    placeholder="e.g. Premium"
                    class="ui-input">
            </div>

            <div class="ui-field">
                <label class="ui-label">Acquisition Source</label>
                <input name="source" type="text"
                    value="{{ old('source', $customer?->source ?? '') }}"
                    placeholder="e.g. Field Visit"
                    class="ui-input">
            </div>
        </x-ui.card>

        {{-- Business Details (Conditional) --}}
        <div x-show="category === 'business'" x-cloak x-transition>
            <x-ui.card class="space-y-5 border-l-4 border-l-primary bg-primary/5">
                <div class="ui-field">
                    <label class="ui-label">Company Legal Name *</label>
                    <input name="company_name" type="text"
                        value="{{ old('company_name', $customer?->company_name ?? '') }}"
                        class="ui-input">
                </div>

                <div class="ui-field">
                    <label class="ui-label">GST Number</label>
                    <input name="gst_number" type="text"
                        value="{{ old('gst_number', $customer?->gst_number ?? '') }}"
                        class="ui-input">
                </div>

                <div class="ui-field">
                    <label class="ui-label">PAN Number</label>
                    <input name="pan_number" type="text"
                        value="{{ old('pan_number', $customer?->pan_number ?? '') }}"
                        class="ui-input">
                </div>
            </x-ui.card>
        </div>

        {{-- Financial --}}
        <x-ui.card class="space-y-5">
            <div class="ui-field">
                <label class="ui-label">Credit Limit (₹)</label>
                <input name="credit_limit" type="number"
                    value="{{ old('credit_limit', $customer?->credit_limit ?? 0) }}"
                    class="ui-input">
            </div>

            <div class="ui-field">
                <label class="ui-label">Payment Terms (Days)</label>
                <input name="payment_terms_days" type="number"
                    value="{{ old('payment_terms_days', $customer?->payment_terms_days ?? 0) }}"
                    class="ui-input">
            </div>
        </x-ui.card>

        {{-- Notes --}}
        <x-ui.card class="p-0 overflow-hidden">
            <textarea name="internal_notes" 
                placeholder="Internal administrative notes..."
                class="w-full border-0 bg-transparent p-4 text-sm focus:ring-0" 
                rows="4">{{ old('internal_notes', $customer?->internal_notes ?? '') }}</textarea>
        </x-ui.card>

        @if(!$modalMode)
            <div class="flex gap-3">
                <x-ui.button type="submit" class="flex-1">
                    {{ $isEdit ? 'Save Changes' : 'Register Customer' }}
                </x-ui.button>
            </div>
        @endif
    </div>
</div>

@if($modalMode)
    <div class="flex items-center justify-end gap-3 pt-6 border-t border-border/60">
        <button type="button" data-modal-close class="btn-secondary">Cancel</button>
        <x-ui.button type="submit">
            {{ $isEdit ? 'Save Profile' : 'Create Profile' }}
        </x-ui.button>
    </div>
@endif
if