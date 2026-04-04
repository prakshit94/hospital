<div class="space-y-6 p-5 sm:p-6 lg:p-7">
    <div class="flex flex-col gap-4 border-b border-border/70 pb-5 lg:flex-row lg:items-start lg:justify-between">
        <div class="space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="{{ $user->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">{{ $user->status }} account</span>
                @if($user->is_online)
                    <span class="ui-status-success">Online</span>
                @else
                    <span class="ui-status-danger">Offline</span>
                @endif
                <span class="ui-chip-muted">User #{{ $user->id }}</span>
            </div>
            <div>
                <h2 class="text-2xl font-black tracking-tight text-foreground sm:text-3xl">{{ $user->name ?: trim("{$user->first_name} {$user->last_name}") }}</h2>
                <p class="mt-2 text-sm text-muted-foreground">{{ $user->email }}</p>
                <p class="mt-1 text-xs font-medium text-muted-foreground uppercase tracking-widest">{{ $user->job_title }} {{ $user->department ? '- ' . $user->department : '' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            @if(auth()->user()?->hasPermission('users.update'))
                <x-ui.button variant="secondary" href="{{ route('users.edit', $user) }}" data-modal-open>Edit User</x-ui.button>
            @endif
            <x-ui.button variant="ghost" type="button" data-modal-close>Close</x-ui.button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Account Overview</div>
                <h3 class="section-title">Identity snapshot</h3>
                <p class="section-copy">Current status, assigned roles, and recent access history.</p>
            </div>

            <div class="detail-grid">
                <div class="detail-tile">
                    <div class="detail-label">Email</div>
                    <div class="detail-value break-all">{{ $user->email }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">{{ $user->phone ?: 'Not provided' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">{{ $user->location ?: 'Not provided' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Gender</div>
                    <div class="detail-value">{{ $user->gender ?: 'Not provided' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Last Login</div>
                    <div class="detail-value">{{ $user->last_login_at?->format('d M Y h:i A') ?? 'Never' }}</div>
                </div>
                <div class="detail-tile md:col-span-2">
                    <div class="detail-label">Assigned Roles</div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse($user->roles as $role)
                            <span class="ui-chip">{{ $role->name }}</span>
                        @empty
                            <span class="ui-chip-muted">No roles assigned</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Effective Access</div>
                <h3 class="section-title">Permission coverage</h3>
                <p class="section-copy">Permissions inherited from the current role mapping.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @forelse($user->permissionSlugs() as $permission)
                    <span class="ui-chip-muted">{{ $permission }}</span>
                @empty
                    <span class="ui-chip-muted">No permissions available</span>
                @endforelse
            </div>
        </x-ui.card>
    </div>

    <x-ui.card class="space-y-4">
        <div>
            <div class="section-kicker">Recent Activity</div>
            <h3 class="section-title">Latest events</h3>
            <p class="section-copy">Recent actions performed by or against this user.</p>
        </div>

        <div class="space-y-3">
            @forelse($activities as $activity)
                <div class="list-card">
                    <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                            <div class="mt-2"><span class="ui-chip">{{ $activity->action }}</span></div>
                        </div>
                        <div class="text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-state">No activity recorded yet.</div>
            @endforelse
        </div>
    </x-ui.card>
</div>
