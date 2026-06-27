@extends('layouts.app')

@php
    $pageTitle = $company->name . ' — Company';
@endphp

@section('content')
    <div class="page-stack">

        {{-- ═══════════════════════ HERO PANEL ═══════════════════════ --}}
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">Business Directory</span>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="{{ $company->is_active ? 'ui-status-success' : 'ui-status-danger' }}">
                            {{ $company->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($company->code)
                            <span class="ui-chip-muted font-mono">{{ $company->code }}</span>
                        @endif
                    </div>
                    <h1 class="hero-title">{{ $company->name }}</h1>
                    <p class="hero-copy">{{ $company->email ?: 'No email on record' }}</p>
                    @if($company->contact_person)
                        <p class="hero-copy mt-1 text-xs font-bold uppercase tracking-widest text-muted-foreground">
                            Contact: {{ $company->contact_person }}
                            @if($company->contact_number)
                                &mdash; {{ $company->contact_number }}
                            @endif
                        </p>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @if(auth()->user()?->hasPermission('companies.update'))
                        <x-ui.button variant="secondary" href="{{ route('companies.edit', $company) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                        </x-ui.button>
                    @endif
                    <x-ui.button variant="ghost" href="{{ route('companies.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
                        Back
                    </x-ui.button>
                </div>
            </div>
        </section>

        {{-- ═══════════════════════ DETAIL GRID ═══════════════════════ --}}
        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.8fr)]">

            {{-- Company Details Card --}}
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Company Overview</div>
                        <h2 class="section-title">Contact & identity</h2>
                        <p class="section-copy">Key details and current status for this corporate client.</p>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-tile">
                        <div class="detail-label">Company Code</div>
                        <div class="detail-value font-mono">{{ $company->code ?: '—' }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="{{ $company->is_active ? 'ui-status-success' : 'ui-status-danger' }}">
                                {{ $company->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value break-all">{{ $company->email ?: 'Not provided' }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Contact Number</div>
                        <div class="detail-value">{{ $company->contact_number ?: 'Not provided' }}</div>
                    </div>
                    <div class="detail-tile md:col-span-2">
                        <div class="detail-label">Contact Person</div>
                        <div class="detail-value">{{ $company->contact_person ?: 'Not provided' }}</div>
                    </div>
                    <div class="detail-tile md:col-span-2">
                        <div class="detail-label">Address</div>
                        <div class="detail-value">{{ $company->address ?: 'Not provided' }}</div>
                    </div>
                </div>
            </x-ui.card>

            {{-- Sidebar: Meta + Actions --}}
            <div class="flex flex-col gap-6">

                {{-- Record Meta --}}
                <x-ui.card class="space-y-5">
                    <div>
                        <div class="section-kicker">Record Info</div>
                        <h2 class="section-title">Timestamps</h2>
                        <p class="section-copy">When this company was created and last changed.</p>
                    </div>
                    <div class="detail-grid">
                        <div class="detail-tile">
                            <div class="detail-label">Created</div>
                            <div class="detail-value">{{ $company->created_at?->format('d M Y') ?? '—' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Last Updated</div>
                            <div class="detail-value">{{ $company->updated_at?->diffForHumans() ?? '—' }}</div>
                        </div>
                    </div>
                </x-ui.card>

                {{-- Quick Actions --}}
                <x-ui.card class="space-y-4">
                    <div>
                        <div class="section-kicker">Quick Actions</div>
                        <h2 class="section-title">Manage company</h2>
                        <p class="section-copy">Perform common operations on this record.</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        @if(auth()->user()?->hasPermission('companies.update'))
                            <a href="{{ route('companies.edit', $company) }}"
                               class="flex items-center gap-3 rounded-[1.2rem] border border-border bg-secondary/40 px-4 py-3 text-sm font-semibold text-foreground transition duration-300 hover:bg-accent hover:text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-primary/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit Company Details
                            </a>
                        @endif
                        @if(auth()->user()?->hasPermission('companies.delete'))
                            <form action="{{ route('companies.destroy', $company) }}" method="POST"
                                  onsubmit="return confirm('Delete this company? This may affect linked health records.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex w-full items-center gap-3 rounded-[1.2rem] border border-destructive/20 bg-destructive/5 px-4 py-3 text-sm font-semibold text-destructive transition duration-300 hover:bg-destructive/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Delete Company
                                </button>
                            </form>
                        @endif
                    </div>
                </x-ui.card>

            </div>
        </section>

        {{-- ═══════════════════════ ACTIVITY FEED ═══════════════════════ --}}
        <x-ui.card>
            <div class="section-header">
                <div>
                    <div class="section-kicker">Audit Trail</div>
                    <h2 class="section-title">Recent activity</h2>
                    <p class="section-copy">The latest actions performed on this company record.</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($activities as $activity)
                    <div class="list-card">
                        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="ui-chip">{{ $activity->action }}</span>
                                    @if($activity->causer)
                                        <span class="ui-chip-muted">by {{ $activity->causer->name ?? 'System' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">
                                {{ $activity->created_at?->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No activity recorded yet.</div>
                @endforelse
            </div>
        </x-ui.card>

    </div>
@endsection
