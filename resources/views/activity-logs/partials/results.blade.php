<div class="overflow-x-auto">
    <table class="min-w-full text-left">
        <thead class="border-b border-border/60 text-[11px] font-black uppercase tracking-[0.22em] text-muted-foreground">
            <tr>
                <th class="px-4 py-3">Action</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Actor</th>
                <th class="px-4 py-3">IP</th>
                <th class="px-4 py-3">When</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
            @forelse($activities as $activity)
                <tr class="transition hover:bg-secondary/25">
                    <td class="px-4 py-4">
                        <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $activity->action }}</span>
                    </td>
                    <td class="px-4 py-4 text-sm text-foreground">{{ $activity->description ?: 'No description' }}</td>
                    <td class="px-4 py-4 text-sm text-muted-foreground">{{ $activity->causer?->name ?? 'System' }}</td>
                    <td class="px-4 py-4 text-sm text-muted-foreground">{{ $activity->ip_address ?? 'N/A' }}</td>
                    <td class="px-4 py-4 text-sm text-muted-foreground">{{ $activity->created_at?->format('d M Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-sm text-muted-foreground">No activity recorded yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="text-sm text-muted-foreground">
        Showing {{ $activities->firstItem() ?? 0 }} to {{ $activities->lastItem() ?? 0 }} of {{ $activities->total() }} records.
        Page {{ $activities->currentPage() }} of {{ $activities->lastPage() }}.
    </div>
    <div>{{ $activities->links() }}</div>
</div>
