@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-bold tracking-tight text-foreground">Employee Health Records</h1>
                <p class="text-muted-foreground">Analyze and manage health data across multiple companies.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('health-records.create') }}" 
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-[0_10px_20px_-5px_color-mix(in_oklab,var(--primary)_50%,transparent)] transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    New Health Record
                </a>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="rounded-[2rem] border border-border bg-card p-6 shadow-sm">
            <form action="{{ route('health-records.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="relative flex-1 min-w-[240px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full rounded-xl border-border bg-secondary/50 py-2.5 pl-11 pr-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20" 
                           placeholder="Search by name, company, or ID...">
                </div>

                <div class="flex items-center gap-3">
                    <select name="company" class="rounded-xl border-border bg-secondary/50 py-2.5 pl-4 pr-10 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company }}" {{ request('company') == $company ? 'selected' : '' }}>{{ $company }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-foreground px-4 text-sm font-medium text-background transition hover:opacity-90">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'company']))
                        <a href="{{ route('health-records.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-border px-4 text-sm font-medium text-foreground transition hover:bg-secondary">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Results Table -->
        <div class="rounded-[2rem] border border-border bg-card shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-border/50 bg-secondary/30">
                            <th class="px-6 py-4 font-bold text-muted-foreground uppercase tracking-wider text-[11px]">Employee Info</th>
                            <th class="px-6 py-4 font-bold text-muted-foreground uppercase tracking-wider text-[11px]">Company & ID</th>
                            <th class="px-6 py-4 font-bold text-muted-foreground uppercase tracking-wider text-[11px]">Vitals</th>
                            <th class="px-6 py-4 font-bold text-muted-foreground uppercase tracking-wider text-[11px]">BMI</th>
                            <th class="px-6 py-4 font-bold text-muted-foreground uppercase tracking-wider text-[11px]">Status</th>
                            <th class="px-6 py-4 font-bold text-muted-foreground uppercase tracking-wider text-[11px] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/40">
                        @forelse($records as $record)
                            <tr class="group transition hover:bg-secondary/20">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary font-bold">
                                            {{ strtoupper(substr($record->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-foreground">{{ $record->full_name }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $record->gender }}, {{ $record->blood_group ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-foreground">{{ $record->company_name }}</div>
                                    <div class="text-xs text-muted-foreground">ID: {{ $record->employee_id ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-bold uppercase text-muted-foreground/60 w-8">BP:</span>
                                            <span class="font-medium">{{ $record->bp_systolic ?? '--' }}/{{ $record->bp_diastolic ?? '--' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-bold uppercase text-muted-foreground/60 w-8">HR:</span>
                                            <span class="font-medium">{{ $record->heart_rate ?? '--' }} bpm</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($record->bmi)
                                        <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold {{ $record->bmi >= 18.5 && $record->bmi <= 24.9 ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $record->bmi }}
                                        </span>
                                    @else
                                        <span class="text-muted-foreground italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-primary/10 px-2.5 py-0.5 text-xs font-bold text-primary">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('health-records.show', $record->uuid) }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-secondary text-muted-foreground transition hover:bg-primary hover:text-white" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('health-records.print', $record->uuid) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-lg bg-secondary text-muted-foreground transition hover:bg-primary hover:text-white" title="Medical Report">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('health-records.print-form32', $record->uuid) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 transition hover:bg-emerald-600 hover:text-white" title="Print Form 32">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('health-records.print-form33', $record->uuid) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-700 transition hover:bg-blue-600 hover:text-white" title="Print Form 33">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-muted-foreground">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-12 text-muted-foreground/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M12 12v6"/>
                                        </svg>
                                        <p>No health records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($records->hasPages())
                <div class="border-t border-border/50 px-6 py-4">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
