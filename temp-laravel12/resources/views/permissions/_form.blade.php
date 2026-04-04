<div class="grid gap-6 xl:grid-cols-3">
    <x-ui.card class="space-y-6 xl:col-span-2">
        <div>
            <h2 class="font-heading text-xl font-bold">Permission Details</h2>
            <p class="mt-1 text-sm text-muted-foreground">Define a reusable system capability that roles can grant.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold">Permission name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $permission->name) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
            </div>
            <div>
                <label for="slug" class="mb-2 block text-sm font-semibold">Slug</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $permission->slug) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" placeholder="auto-generated">
            </div>
            <div class="md:col-span-2">
                <label for="group_name" class="mb-2 block text-sm font-semibold">Group</label>
                <input id="group_name" name="group_name" type="text" value="{{ old('group_name', $permission->group_name) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" placeholder="users" required>
            </div>
        </div>

        <div>
            <label for="description" class="mb-2 block text-sm font-semibold">Description</label>
            <textarea id="description" name="description" rows="4" class="block w-full rounded-3xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">{{ old('description', $permission->description) }}</textarea>
        </div>
    </x-ui.card>

    <div class="space-y-6">
        <x-ui.card class="space-y-3">
            <x-ui.button class="w-full justify-center">{{ $submitLabel }}</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('permissions.index') }}" class="w-full justify-center">Cancel</x-ui.button>
        </x-ui.card>
    </div>
</div>
