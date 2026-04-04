@php
    $selectedPermissions = old('permissions', isset($role) ? $role->permissions->pluck('id')->all() : []);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,1.25fr)_minmax(340px,0.85fr)]">
    <x-ui.card class="space-y-6">
        <div>
            <div class="section-kicker">Role Details</div>
            <h2 class="section-title">Structure the access profile</h2>
            <p class="section-copy">Define the role label and the permissions it grants to the users assigned under it.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="ui-field">
                <label for="name" class="ui-label">Role Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $role->name) }}" class="ui-input" required>
            </div>
            <div class="ui-field">
                <label for="slug" class="ui-label">Slug</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $role->slug) }}" class="ui-input" placeholder="auto-generated">
            </div>
            <div class="ui-field md:col-span-2">
                <label for="description" class="ui-label">Description</label>
                <textarea id="description" name="description" rows="5" class="ui-textarea">{{ old('description', $role->description) }}</textarea>
            </div>
        </div>
    </x-ui.card>

    <div class="space-y-6">
        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Permission Matrix</div>
                <h2 class="section-title">Attach abilities</h2>
                <p class="section-copy">Choose the capabilities available to users operating under this role.</p>
            </div>

            <div class="space-y-5 max-h-[32rem] overflow-y-auto pr-1">
                @foreach($permissionGroups as $group => $permissions)
                    <div class="space-y-3">
                        <div class="text-[11px] font-black uppercase tracking-[0.22em] text-muted-foreground">{{ str_replace('_', ' ', $group) }}</div>
                        @foreach($permissions as $permission)
                            <label class="ui-checkbox-card">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="mt-1 h-4 w-4" @checked(in_array($permission->id, $selectedPermissions))>
                                <span>
                                    <span class="block text-sm font-semibold text-foreground">{{ $permission->name }}</span>
                                    <span class="mt-1 block text-xs text-muted-foreground">{{ $permission->slug }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-3">
            <x-ui.button class="w-full justify-center">{{ $submitLabel }}</x-ui.button>
            @if($modalMode ?? false)
                <button type="button" data-modal-close class="inline-flex w-full items-center justify-center gap-2 rounded-[1.2rem] border border-border bg-secondary px-4 py-2.5 text-sm font-semibold text-foreground transition duration-300 active:scale-[0.98] hover:bg-accent">
                    Cancel
                </button>
            @else
                <x-ui.button variant="secondary" href="{{ route('roles.index') }}" class="w-full justify-center">Cancel</x-ui.button>
            @endif
        </x-ui.card>
    </div>
</div>
