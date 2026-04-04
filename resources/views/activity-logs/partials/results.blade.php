<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th>Action</th>
                <th>Description</th>
                <th>Actor</th>
                <th>IP</th>
                <th>When</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
                <tr>
                    <td data-label="Action"><span class="ui-chip">{{ $activity->action }}</span></td>
                    <td data-label="Description" class="table-primary">{{ $activity->description ?: 'No description' }}</td>
                    <td data-label="Actor" class="table-secondary">{{ $activity->causer?->name ?? 'System' }}</td>
                    <td data-label="IP" class="table-secondary">{{ $activity->ip_address ?? 'N/A' }}</td>
                    <td data-label="When" class="table-secondary">{{ $activity->created_at?->format('d M Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">No activity recorded yet.</div>
                    </td>
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
