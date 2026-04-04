<div class="space-y-6 p-5 sm:p-6 lg:p-7">
    <div class="flex flex-col gap-4 border-b border-border/70 pb-5 lg:flex-row lg:items-start lg:justify-between">
        <div class="space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="ui-chip-muted">{{ $permission->group_name }}</span>
                <span class="ui-chip">{{ $permission->slug }}</span>
            </div>
            <div>
                <h2 class="text-2xl font-black tracking-tight text-foreground sm:text-3xl">{{ $permission->name }}</h2>
                <p class="mt-2 text-sm text-muted-foreground">{{ $permission->description ?: 'No description provided for this permission.' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            @if(auth()->user()?->hasPermission('permissions.update'))
                <x-ui.button variant="secondary" href="{{ route('permissions.edit', $permission) }}" data-modal-open>Edit Permission</x-ui.button>
            @endif
            <x-ui.button variant="ghost" type="button" data-modal-close>Close</x-ui.button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)]">
        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Permission Metadata</div>
                <h3 class="section-title">Operational details</h3>
                <p class="section-copy">Core identifiers and assignment summary for this permission.</p>
            </div>

            <div class="detail-grid">
                <div class="detail-tile">
                    <div class="detail-label">Slug</div>
                    <div class="detail-value break-all">{{ $permission->slug }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Group</div>
                    <div class="detail-value">{{ $permission->group_name }}</div>
                </div>
                <div class="detail-tile md:col-span-2">
                    <div class="detail-label">Roles Using This Permission</div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse($permission->roles as $role)
                            <span class="ui-chip">{{ $role->name }}</span>
                        @empty
                            <span class="ui-chip-muted">No roles linked</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Recent Activity</div>
                <h3 class="section-title">Change history</h3>
                <p class="section-copy">Recent audit entries touching this permission.</p>
            </div>

            <div class="space-y-3">
                @forelse($activities as $activity)
                    <div class="list-card text-sm">
                        <div class="font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                        <div class="mt-1 text-xs text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="empty-state">No activity recorded for this permission.</div>
                @endforelse
            </div>
        </x-ui.card>
    </div>
</div>
