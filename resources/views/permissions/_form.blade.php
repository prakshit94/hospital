<div class="grid gap-6 xl:grid-cols-[minmax(0,1.25fr)_minmax(320px,0.75fr)]">
    <x-ui.card class="space-y-6">
        <div>
            <div class="section-kicker">Permission Details</div>
            <h2 class="section-title">Define a reusable ability</h2>
            <p class="section-copy">Create a granular system capability that can be attached to roles and reused across the workspace.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="ui-field">
                <label for="name" class="ui-label">Permission Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $permission->name) }}" class="ui-input" required>
            </div>
            <div class="ui-field">
                <label for="slug" class="ui-label">Slug</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $permission->slug) }}" class="ui-input" placeholder="auto-generated">
            </div>
            <div class="ui-field md:col-span-2">
                <label for="group_name" class="ui-label">Group</label>
                <input id="group_name" name="group_name" type="text" value="{{ old('group_name', $permission->group_name) }}" class="ui-input" placeholder="users" required>
            </div>
            <div class="ui-field md:col-span-2">
                <label for="description" class="ui-label">Description</label>
                <textarea id="description" name="description" rows="5" class="ui-textarea">{{ old('description', $permission->description) }}</textarea>
            </div>
        </div>
    </x-ui.card>

    <div class="space-y-6">
        <x-ui.card class="space-y-4">
            <div>
                <div class="section-kicker">Design Note</div>
                <h2 class="section-title">Keep permission names clear</h2>
                <p class="section-copy">A strong permission model reads well in logs, exports, and role assignment screens.</p>
            </div>
            <div class="list-card text-sm text-muted-foreground">
                Prefer action-oriented names and consistent grouping so the permission catalog stays easy to audit.
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-3">
            <x-ui.button class="w-full justify-center">{{ $submitLabel }}</x-ui.button>
            @if($modalMode ?? false)
                <button type="button" data-modal-close class="inline-flex w-full items-center justify-center gap-2 rounded-[1.2rem] border border-border bg-secondary px-4 py-2.5 text-sm font-semibold text-foreground transition duration-300 active:scale-[0.98] hover:bg-accent">
                    Cancel
                </button>
            @else
                <x-ui.button variant="secondary" href="{{ route('permissions.index') }}" class="w-full justify-center">Cancel</x-ui.button>
            @endif
        </x-ui.card>
    </div>
</div>
