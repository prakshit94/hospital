<div class="grid grid-cols-2 gap-6">
    <div class="col-span-2 space-y-2">
        <label class="text-sm font-bold text-foreground/80">Company Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" required value="{{ old('name', $company->name ?? '') }}"
               class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-foreground/80">Company Code</label>
        <input type="text" name="code" value="{{ old('code', $company->code ?? '') }}" placeholder="e.g. ABC"
               class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
        @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-foreground/80">Email Address</label>
        <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}"
               class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-foreground/80">Contact Person</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $company->contact_person ?? '') }}"
               class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-foreground/80">Contact Number</label>
        <input type="text" name="contact_number" value="{{ old('contact_number', $company->contact_number ?? '') }}"
               class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-foreground/80">Status</label>
        <select name="is_active" class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            <option value="1" {{ old('is_active', $company->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $company->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="col-span-2 space-y-2">
        <label class="text-sm font-bold text-foreground/80">Address</label>
        <textarea name="address" rows="3" class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">{{ old('address', $company->address ?? '') }}</textarea>
    </div>
</div>
