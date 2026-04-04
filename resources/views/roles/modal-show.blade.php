<div class="space-y-6 p-5 sm:p-6 lg:p-7">
    <div class="flex flex-col gap-4 border-b border-border/70 pb-5 lg:flex-row lg:items-start lg:justify-between">
        <div class="space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="ui-chip">{{ $role->slug }}</span>
                @if($role->is_system)
                    <span class="ui-chip-muted">System role</span>
                @endif
            </div>
            <div>
                <h2 class="text-2xl font-black tracking-tight text-foreground sm:text-3xl">{{ $role->name }}</h2>
                <p class="mt-2 text-sm text-muted-foreground">{{ $role->description ?: 'No description provided for this role.' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            @if(auth()->user()?->hasPermission('roles.update'))
                <x-ui.button variant="secondary" href="{{ route('roles.edit', $role) }}" data-modal-open>Edit Role</x-ui.button>
            @endif
            <x-ui.button variant="ghost" type="button" data-modal-close>Close</x-ui.button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Permissions</div>
                <h3 class="section-title">Granted abilities</h3>
                <p class="section-copy">Capabilities this role contributes to assigned users.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @forelse($role->permissions as $permission)
                    <span class="ui-chip">{{ $permission->slug }}</span>
                @empty
                    <span class="ui-chip-muted">No permissions assigned</span>
                @endforelse
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Assigned Users</div>
                <h3 class="section-title">People using this role</h3>
                <p class="section-copy">Accounts currently mapped to this access profile.</p>
            </div>

            <div class="space-y-3">
                @forelse($role->users as $user)
                    <div class="list-card text-sm">
                        <div class="font-semibold text-foreground">{{ $user->name }}</div>
                        <div class="mt-1 text-muted-foreground">{{ $user->email }}</div>
                    </div>
                @empty
                    <div class="empty-state">No users are assigned to this role.</div>
                @endforelse
            </div>
        </x-ui.card>
    </div>

    <x-ui.card class="space-y-4">
        <div>
            <div class="section-kicker">Recent Activity</div>
            <h3 class="section-title">Role change history</h3>
            <p class="section-copy">Recent audit entries affecting this role.</p>
        </div>

        <div class="space-y-3">
            @forelse($activities as $activity)
                <div class="list-card">
                    <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                    <div class="mt-2 text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                </div>
            @empty
                <div class="empty-state">No activity recorded for this role.</div>
            @endforelse
        </div>
    </x-ui.card>
</div>
