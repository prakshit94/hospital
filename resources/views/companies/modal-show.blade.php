<div class="space-y-6 p-5 sm:p-6 lg:p-7">
    <div class="flex flex-col gap-4 border-b border-border/70 pb-5 lg:flex-row lg:items-start lg:justify-between">
        <div class="space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="{{ $company->is_active ? 'ui-status-success' : 'ui-status-danger' }}">
                    {{ $company->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($company->code)
                    <span class="ui-chip-muted font-mono">{{ $company->code }}</span>
                @endif
                <span class="ui-chip-muted">Company #{{ $company->id }}</span>
            </div>
            <div>
                <h2 class="text-2xl font-black tracking-tight text-foreground sm:text-3xl">{{ $company->name }}</h2>
                <p class="mt-2 text-sm text-muted-foreground">{{ $company->email ?: 'No email on record' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            @if(auth()->user()?->hasPermission('companies.update'))
                <x-ui.button variant="secondary" href="{{ route('companies.edit', $company) }}" data-modal-open>Edit Company</x-ui.button>
            @endif
            <x-ui.button variant="ghost" type="button" data-modal-close>Close</x-ui.button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(280px,0.8fr)]">
        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Company Overview</div>
                <h3 class="section-title">Contact & identity</h3>
                <p class="section-copy">Key contact details and current status for this corporate client.</p>
            </div>

            <div class="detail-grid">
                <div class="detail-tile">
                    <div class="detail-label">Company Code</div>
                    <div class="detail-value font-mono">{{ $company->code ?: '---' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value break-all">{{ $company->email ?: 'Not provided' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Contact Person</div>
                    <div class="detail-value">{{ $company->contact_person ?: 'Not provided' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Contact Number</div>
                    <div class="detail-value">{{ $company->contact_number ?: 'Not provided' }}</div>
                </div>
                <div class="detail-tile md:col-span-2">
                    <div class="detail-label">Address</div>
                    <div class="detail-value">{{ $company->address ?: 'Not provided' }}</div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Record Info</div>
                <h3 class="section-title">Timestamps</h3>
                <p class="section-copy">When this record was created and last modified.</p>
            </div>

            <div class="detail-grid">
                <div class="detail-tile">
                    <div class="detail-label">Created</div>
                    <div class="detail-value">{{ $company->created_at?->format('d M Y') ?? '---' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value">{{ $company->updated_at?->diffForHumans() ?? '---' }}</div>
                </div>
            </div>
        </x-ui.card>
    </div>

    <x-ui.card class="space-y-4">
        <div>
            <div class="section-kicker">Recent Activity</div>
            <h3 class="section-title">Latest events</h3>
            <p class="section-copy">Recent actions performed on this company record.</p>
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
