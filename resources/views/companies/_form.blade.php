<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

    {{-- Company Name --}}
    <div class="sm:col-span-2 space-y-2">
        <label class="ui-label">Company Name <span class="text-destructive">*</span></label>
        <input
            type="text"
            name="name"
            required
            value="{{ old('name', $company->name ?? '') }}"
            placeholder="e.g. Divit Healthcare Pvt. Ltd."
            class="{{ $errors->has('name') ? 'modal-field-error' : '' }}"
        >
        @error('name')
            <p class="ui-hint text-destructive">{{ $message }}</p>
        @enderror
    </div>

    {{-- Company Code --}}
    <div class="space-y-2">
        <label class="ui-label">Company Code</label>
        <input
            type="text"
            name="code"
            value="{{ old('code', $company->code ?? '') }}"
            placeholder="e.g. DIV"
            class="{{ $errors->has('code') ? 'modal-field-error' : '' }}"
        >
        @error('code')
            <p class="ui-hint text-destructive">{{ $message }}</p>
        @enderror
    </div>

    {{-- Status --}}
    <div class="space-y-2">
        <label class="ui-label">Status</label>
        <select name="is_active" class="{{ $errors->has('is_active') ? 'modal-field-error' : '' }}">
            <option value="1" {{ old('is_active', $company->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $company->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('is_active')
            <p class="ui-hint text-destructive">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div class="space-y-2">
        <label class="ui-label">Email Address</label>
        <input
            type="email"
            name="email"
            value="{{ old('email', $company->email ?? '') }}"
            placeholder="contact@company.com"
            class="{{ $errors->has('email') ? 'modal-field-error' : '' }}"
        >
        @error('email')
            <p class="ui-hint text-destructive">{{ $message }}</p>
        @enderror
    </div>

    {{-- Contact Person --}}
    <div class="space-y-2">
        <label class="ui-label">Contact Person</label>
        <input
            type="text"
            name="contact_person"
            value="{{ old('contact_person', $company->contact_person ?? '') }}"
            placeholder="Full name"
            class="{{ $errors->has('contact_person') ? 'modal-field-error' : '' }}"
        >
        @error('contact_person')
            <p class="ui-hint text-destructive">{{ $message }}</p>
        @enderror
    </div>

    {{-- Contact Number --}}
    <div class="space-y-2">
        <label class="ui-label">Contact Number</label>
        <input
            type="text"
            name="contact_number"
            value="{{ old('contact_number', $company->contact_number ?? '') }}"
            placeholder="+91 98765 43210"
            class="{{ $errors->has('contact_number') ? 'modal-field-error' : '' }}"
        >
        @error('contact_number')
            <p class="ui-hint text-destructive">{{ $message }}</p>
        @enderror
    </div>

    {{-- Address --}}
    <div class="sm:col-span-2 space-y-2">
        <label class="ui-label">Address</label>
        <textarea
            name="address"
            rows="3"
            placeholder="Full postal address..."
            class="ui-textarea {{ $errors->has('address') ? 'modal-field-error' : '' }}"
        >{{ old('address', $company->address ?? '') }}</textarea>
        @error('address')
            <p class="ui-hint text-destructive">{{ $message }}</p>
        @enderror
    </div>

</div>
