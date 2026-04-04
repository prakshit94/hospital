@php
    $selectedPermissions = old('permissions', isset($role) ? $role->permissions->pluck('id')->all() : []);
@endphp

<div class="grid gap-6 xl:grid-cols-3">
    <x-ui.card class="space-y-6 xl:col-span-2">
        <div>
            <h2 class="font-heading text-xl font-bold">Role Details</h2>
            <p class="mt-1 text-sm text-muted-foreground">Define the role label and the permissions it grants.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold">Role name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $role->name) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
            </div>
            <div>
                <label for="slug" class="mb-2 block text-sm font-semibold">Slug</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $role->slug) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" placeholder="auto-generated">
            </div>
        </div>

        <div>
            <label for="description" class="mb-2 block text-sm font-semibold">Description</label>
            <textarea id="description" name="description" rows="4" class="block w-full rounded-3xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">{{ old('description', $role->description) }}</textarea>
        </div>
    </x-ui.card>

    <div class="space-y-6">
        <x-ui.card class="space-y-4">
            <div>
                <h2 class="font-heading text-xl font-bold">Permissions</h2>
                <p class="mt-1 text-sm text-muted-foreground">Choose the capabilities available to users with this role.</p>
            </div>

            <div class="space-y-4">
                @foreach($permissionGroups as $group => $permissions)
                    <div class="space-y-3">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">{{ str_replace('_', ' ', $group) }}</div>
                        @foreach($permissions as $permission)
                            <label class="flex items-start gap-3 rounded-2xl border border-border/60 bg-secondary/25 px-4 py-3">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="mt-1 h-4 w-4 rounded border-border text-primary focus:ring-primary" @checked(in_array($permission->id, $selectedPermissions))>
                                <span>
                                    <span class="block text-sm font-semibold">{{ $permission->name }}</span>
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
            <x-ui.button variant="secondary" href="{{ route('roles.index') }}" class="w-full justify-center">Cancel</x-ui.button>
        </x-ui.card>
    </div>
</div>
