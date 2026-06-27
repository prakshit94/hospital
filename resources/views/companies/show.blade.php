@extends('layouts.app')

@php
    $pageTitle = 'Company Profile';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="flex flex-wrap gap-2 items-center mb-1">
                        <span class="{{ $company->is_active ? 'ui-status-success' : 'ui-status-danger' }}">
                            {{ $company->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($company->code)
                            <span class="ui-chip-muted font-mono">{{ $company->code }}</span>
                        @endif
                    </div>
                    <h1 class="hero-title">{{ $company->name }}</h1>
                    <p class="hero-copy">{{ $company->email ?: 'No email on record' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if(auth()->user()?->hasPermission('companies.update'))
                        <x-ui.button variant="secondary" href="{{ route('companies.edit', $company) }}">Edit</x-ui.button>
                    @endif
                    <x-ui.button variant="ghost" href="{{ route('companies.index') }}">Back to Companies</x-ui.button>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.8fr)]">
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Company Overview</div>
                        <h2 class="section-title">Company details</h2>
                        <p class="section-copy">Contact information and current status for this corporate client.</p>
                    </div>
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
                    <div class="detail-tile">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="ui-chip {{ $company->is_active ? '!bg-emerald-500/10 !text-emerald-600' : '!bg-amber-500/10 !text-amber-600' }}">
                                {{ $company->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Created</div>
                        <div class="detail-value">{{ $company->created_at?->format('d M Y') ?? '---' }}</div>
                    </div>
                    <div class="detail-tile md:col-span-2">
                        <div class="detail-label">Address</div>
                        <div class="detail-value">{{ $company->address ?: 'Not provided' }}</div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Quick Actions</div>
                    <h2 class="section-title">Manage company</h2>
                    <p class="section-copy">Perform common operations on this company record.</p>
                </div>
                <div class="flex flex-col gap-3">
                    @if(auth()->user()?->hasPermission('companies.update'))
                        <a href="{{ route('companies.edit', $company) }}"
                           class="flex items-center gap-3 rounded-xl border border-border bg-secondary/30 px-4 py-3 text-sm font-semibold text-foreground transition hover:bg-secondary hover:text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit Company Details
                        </a>
                    @endif
                    @if(auth()->user()?->hasPermission('companies.delete'))
                        <form action="{{ route('companies.destroy', $company) }}" method="POST"
                              onsubmit="return confirm('Delete this company? This may affect linked health records.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="flex w-full items-center gap-3 rounded-xl border border-destructive/20 bg-destructive/5 px-4 py-3 text-sm font-semibold text-danger transition hover:bg-destructive/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Delete Company
                            </button>
                        </form>
                    @endif
                </div>
            </x-ui.card>
        </section>

        <x-ui.card>
            <div class="section-header">
                <div>
                    <div class="section-kicker">Recent Activity</div>
                    <h2 class="section-title">Latest events around this company</h2>
                    <p class="section-copy">Recent actions performed on this company record.</p>
                </div>
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
@endsection
