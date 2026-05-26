@extends('layouts.app')

@php
    $pageTitle = 'Dashboard';
@endphp

@section('content')
<div class="page-stack">

    <!-- 🔷 HERO SECTION -->
    <section class="hero-panel overflow-hidden relative">
        <!-- Abstract background decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="grid gap-8 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.8fr)] xl:items-center relative z-10">

            <!-- LEFT -->
            <div>
                <span class="hero-kicker">Operations Hub</span>
                <h1 class="hero-title">Healthcare System Overview</h1>
                <p class="hero-copy">
                    Centralized management of corporate health records, partner companies, and system security.
                </p>

                <!-- Actions -->
                <div class="hero-actions flex flex-wrap gap-3">
                    @can('health_records.create')
                        <x-ui.button variant="secondary" href="{{ route('health-records.create') }}" class="group shadow-lg shadow-emerald-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            New Health Record
                        </x-ui.button>
                    @endcan

                    @can('companies.create')
                        <x-ui.button href="{{ route('companies.create') }}" data-modal-open class="group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18" />
                            </svg>
                            Register Company
                        </x-ui.button>
                    @endcan

                    <x-ui.button variant="ghost" href="{{ route('reports.index') }}">
                        Generate Reports
                    </x-ui.button>
                </div>

                <!-- Inline Stats -->
                <div class="hero-inline-metrics mt-10">
                    @foreach($stats as $stat)
                        <div class="hero-inline-metric group hover:translate-y-[-2px] transition-all duration-300">
                            <div class="hero-inline-metric-label group-hover:text-primary transition-colors">{{ $stat['label'] }}</div>
                            <div class="hero-inline-metric-value">{{ $stat['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- RIGHT - Quick Overview Cards -->
            <div class="grid gap-4">
                <div class="metric-card bg-emerald-500/[0.03] border-emerald-500/10 hover:bg-emerald-500/[0.05] transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="metric-label text-emerald-600 font-bold">Live Status</div>
                        <div class="size-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    </div>
                    <div class="space-y-3">
                        @forelse($activities->take(2) as $activity)
                            <div class="p-3 rounded-xl bg-white/50 border border-white shadow-sm">
                                <div class="text-xs font-semibold text-foreground truncate">
                                    {{ $activity->description ?: $activity->getActionLabel() }}
                                </div>
                                <div class="mt-1 text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">
                                    {{ $activity->created_at?->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="empty-state text-xs py-4">System is quiet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- 🔷 METRIC GRID -->
    <section class="metric-grid mt-6">
        @foreach($stats as $index => $stat)
            <div class="metric-card group hover:shadow-xl hover:shadow-primary/5 transition-all duration-500">
                <div class="metric-icon group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 {{ $index === 0 ? 'bg-emerald-500/10 text-emerald-600' : ($index === 1 ? 'bg-teal-500/10 text-teal-600' : 'bg-slate-500/10 text-slate-600') }}">
                    @if($stat['label'] === 'Companies')
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    @elseif($stat['label'] === 'Total Checkups')
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    @elseif($stat['label'] === 'Employees')
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    @elseif($stat['label'] === 'Users')
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    @else
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @endif
                </div>

                <div class="metric-label">{{ $stat['label'] }}</div>
                <div class="metric-value">{{ $stat['value'] }}</div>
                <div class="metric-meta group-hover:text-primary transition-colors">{{ $stat['change'] }}</div>
            </div>
        @endforeach
    </section>

    <!-- 🔷 MAIN CONTENT -->
    <section class="grid gap-6 mt-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(300px,0.75fr)]">

        <!-- Recent Records -->
        <x-ui.card class="relative overflow-hidden">
            <div class="section-header border-b border-border/50 pb-6 mb-6">
                <div>
                    <div class="section-kicker">Examinations</div>
                    <h2 class="section-title">Recent Health Records</h2>
                    <p class="section-copy">Latest employee health assessments across all companies.</p>
                </div>
                <x-ui.button variant="ghost" href="{{ route('health-records.index') }}" class="group">
                    View All <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </x-ui.button>
            </div>

            <div class="space-y-2">
                @forelse($recentRecords as $record)
                    <a href="{{ route('health-records.show', $record->uuid) }}" class="flex items-center justify-between p-4 rounded-2xl hover:bg-secondary/50 border border-transparent hover:border-border transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="size-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-black text-xs">
                                {{ substr($record->full_name, 0, 2) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-foreground group-hover:text-primary transition-colors">{{ $record->full_name }}</div>
                                <div class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">{{ $record->company_name }} • {{ $record->employee_id }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-bold text-foreground">{{ $record->examination_date?->format('M d, Y') ?? 'N/A' }}</div>
                                <div class="text-[10px] text-muted-foreground">Recorded by {{ $record->creator?->name ?? 'System' }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-muted-foreground/30 group-hover:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="empty-state py-12">
                        <div class="size-12 rounded-2xl bg-secondary flex items-center justify-center mx-auto mb-4 text-muted-foreground/40">
                             <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        No health records found yet.
                    </div>
                @endforelse
            </div>
        </x-ui.card>

        <!-- Right Side Widgets -->
        <div class="space-y-6">
            <!-- Top Companies -->
            <x-ui.card>
                 <div class="section-header mb-6">
                    <div>
                        <div class="section-kicker">Partners</div>
                        <h2 class="section-title">Active Companies</h2>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($companies as $company)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-black text-[10px]">
                                    {{ $company->code ?: substr($company->name, 0, 2) }}
                                </div>
                                <div class="text-sm font-bold text-foreground truncate max-w-[120px]">{{ $company->name }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- Export Actions (Visible on Hover) -->
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('companies.export', [$company->id, 'format' => 'excel']) }}" title="Export Excel" class="p-1 rounded-md hover:bg-emerald-500/10 text-emerald-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                                    </a>
                                    <a href="{{ route('companies.export', [$company->id, 'format' => 'pdf']) }}" title="Export PDF" class="p-1 rounded-md hover:bg-rose-500/10 text-rose-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h1a2 2 0 0 1 0 4h-1v-4Z"/><path d="M17 15h-3v4"/><path d="M14 17h2"/><circle cx="12" cy="17" r="2"/></svg>
                                    </a>
                                </div>
                                <div class="px-2 py-1 rounded-md bg-secondary text-[10px] font-black text-muted-foreground group-hover:bg-emerald-500/10 group-hover:text-emerald-600 transition-colors">
                                    {{ $company->health_checkups_count }} Records
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <a href="{{ route('companies.index') }}" class="block text-center text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground hover:text-primary transition-colors mt-4">
                        View All Companies
                    </a>
                </div>
            </x-ui.card>

            <!-- Recent Activity Mini -->
            <x-ui.card>
                <div class="section-header mb-6">
                    <div>
                        <div class="section-kicker">Audit</div>
                        <h2 class="section-title">Activity Log</h2>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($activities->take(5) as $activity)
                        <div class="relative pl-6 pb-4 border-l border-border/50 last:border-0 last:pb-0">
                            <div class="absolute left-[-5px] top-0 size-2.5 rounded-full bg-border border-2 border-card transition-colors {{ str_contains($activity->action, 'created') ? 'bg-emerald-500' : (str_contains($activity->action, 'deleted') ? 'bg-rose-500' : 'bg-teal-500') }}"></div>
                            <div class="text-xs font-bold text-foreground leading-tight">{{ $activity->description ?: $activity->getActionLabel() }}</div>
                            <div class="mt-1 text-[10px] text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

    </section>
</div>
@endsection