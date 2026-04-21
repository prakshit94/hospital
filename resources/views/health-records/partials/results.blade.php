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
                                {{ strtoupper(substr($record->first_name, 0, 1)) }}
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
                            <a href="{{ route('health-records.print', $record->uuid) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-lg bg-secondary text-muted-foreground transition hover:bg-primary hover:text-white" title="Print">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
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
