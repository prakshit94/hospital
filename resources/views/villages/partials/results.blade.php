<div x-data="{
    selected: [],
    selectAll: false,
    allIds: {{ $villages->pluck('uuid')->toJson() }},
    toggleAll() {
        this.selected = this.selectAll ? [...this.allIds] : [];
    },
    bulkAction(action) {
        if (this.selected.length === 0) return;
        
        let confirmMsg = 'Are you sure you want to perform this action?';
        if (action === 'delete') confirmMsg = 'Are you sure you want to delete selected villages?';
        
        if (action === 'delete' && !confirm(confirmMsg)) return;

        fetch('{{ route('villages.bulk-action') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action: action, ids: this.selected })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'success', title: 'Success', description: data.message }}));
                document.querySelector('form[data-async-search]').dispatchEvent(new Event('submit'));
            } else {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'error', title: 'Error', description: data.message }}));
            }
            this.selected = [];
            this.selectAll = false;
        });
    }
}">
    <!-- Bulk Actions Ribbon -->
    <div class="flex items-center gap-4 bg-primary/5 py-2 px-4 rounded-lg border border-primary/20 mb-4 transition-all" style="display: none;" x-show="selected.length > 0">
        <span class="text-sm font-semibold text-primary"><span x-text="selected.length"></span> selected</span>
        <div class="h-4 w-px bg-border"></div>
        <div class="flex gap-2">
            <x-ui.button variant="secondary" size="sm" @click="bulkAction('active')" class="text-[10px] uppercase tracking-wider">Mark Active</x-ui.button>
            <x-ui.button variant="secondary" size="sm" @click="bulkAction('inactive')" class="text-[10px] uppercase tracking-wider">Mark Inactive</x-ui.button>
            <x-ui.button variant="danger" size="sm" @click="bulkAction('delete')" class="text-[10px] uppercase tracking-wider">Delete</x-ui.button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th class="w-10">
                        <input type="checkbox" class="ui-checkbox" x-model="selectAll" @change="toggleAll">
                    </th>
                    <th class="w-10">#</th>
                    <th>Village Name</th>
                    <th>Pincode</th>
                    <th>Taluka / District</th>
                    <th>Serviceable</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($villages as $village)
                    <tr class="hover:bg-muted/30 transition-colors">
                        <td>
                            <input type="checkbox" class="ui-checkbox" value="{{ $village->uuid }}" x-model="selected" @change="selectAll = selected.length === allIds.length">
                        </td>
                        <td class="table-secondary font-mono text-xs">{{ ($villages->currentPage() - 1) * $villages->perPage() + $loop->iteration }}</td>
                        <td data-label="Village">
                            <div class="table-primary">{{ $village->village_name }}</div>
                            <div class="text-[10px] text-muted-foreground uppercase tracking-tight">{{ $village->village_code ?? 'NO-CODE' }}</div>
                        </td>
                        <td data-label="Pincode" class="table-secondary font-mono">{{ $village->pincode }}</td>
                        <td data-label="Location">
                            <div class="table-primary text-xs">{{ $village->taluka_name }}</div>
                            <div class="table-secondary text-[10px]">{{ $village->district_name }}, {{ $village->state_name }}</div>
                        </td>
                        <td data-label="Serviceable">
                            @if($village->is_serviceable)
                                <span class="inline-flex items-center gap-1.5 text-emerald-600 font-bold text-[10px] uppercase">
                                    <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Yes
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-slate-400 font-bold text-[10px] uppercase text-muted-foreground">
                                    No
                                </span>
                            @endif
                        </td>
                        <td data-label="Status">
                            <x-ui.toggle
                                :active="$village->status === 'active'"
                                :action="route('villages.toggle-status', $village->uuid)"
                            />
                        </td>
                        <td data-label="Actions" class="text-right">
                            <div class="flex justify-end gap-1">
                                <x-ui.button variant="ghost" size="sm" @click="$dispatch('edit-village', {{ $village->toJson() }})" class="size-8 p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="m16.5 3.5 4 4L7 21H3v-4z"/></svg>
                                </x-ui.button>
                                <x-ui.button variant="ghost" size="sm" @click="$dispatch('delete-village', '{{ $village->uuid }}')" class="size-8 p-0 text-danger hover:text-danger hover:bg-danger/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                </x-ui.button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">No villages found matching your criteria.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $villages->links() }}
    </div>
</div>
