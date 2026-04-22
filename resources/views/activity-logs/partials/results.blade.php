<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th class="w-10">#</th>
                <th>Action</th>
                <th>Description</th>
                <th>Actor</th>
                <th class="text-right">Details</th>
                <th>IP</th>
                <th>When</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
                <tr x-data="{ expanded: false }">
                    <td class="table-secondary font-mono text-xs">{{ ($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration }}</td>
                    <td data-label="Action">
                        <span class="ui-chip transition-all duration-200" :class="expanded ? 'ring-2 ring-primary/20 bg-primary/10 text-primary' : ''">
                            {{ $activity->action }}
                        </span>
                    </td>
                    <td data-label="Description" class="table-primary">{{ $activity->description ?: 'No description' }}</td>
                    <td data-label="Actor" class="table-secondary">
                        <div class="flex items-center gap-2">
                            <div class="size-6 rounded-full bg-secondary flex items-center justify-center text-[10px] font-bold text-muted-foreground">
                                {{ substr($activity->causer?->name ?? 'S', 0, 1) }}
                            </div>
                            {{ $activity->causer?->name ?? 'System' }}
                        </div>
                    </td>
                    <td data-label="Details" class="text-right">
                        @if(!empty($activity->properties))
                            <button type="button" @click="expanded = !expanded" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-bold transition duration-200" :class="expanded ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-muted-foreground hover:bg-primary/10 hover:text-primary'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                <span x-text="expanded ? 'Hide' : 'Details'">Details</span>
                            </button>
                        @else
                            <span class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground/40 px-2">No Data</span>
                        @endif
                    </td>
                    <td data-label="IP" class="table-secondary font-mono text-[10px]">{{ $activity->ip_address ?? 'N/A' }}</td>
                    <td data-label="When" class="table-secondary text-xs">{{ $activity->created_at?->format('d M Y h:i A') }}</td>
                </tr>
                <tr x-show="expanded" x-cloak>
                    <td colspan="7" class="bg-secondary/30 p-0 border-y border-primary/10">
                        <div class="px-6 py-4 animate-in slide-in-from-top-2 duration-300">
                                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                    {{-- Attribute Changes --}}
                                    @php
                                        $keys = array_unique(array_merge(array_keys($activity->properties['old'] ?? []), array_keys($activity->properties['new'] ?? [])));
                                    @endphp
                                    @if(count($keys) > 0)
                                        <div class="overflow-hidden rounded-xl border border-border bg-card shadow-sm">
                                            <div class="border-b border-border bg-muted/50 px-4 py-2.5 text-[11px] font-bold uppercase tracking-wider text-muted-foreground">Data Comparison</div>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-xs border-collapse">
                                                    <thead>
                                                        <tr class="bg-muted/20">
                                                            <th class="px-4 py-2 text-left font-semibold text-muted-foreground border-b border-border w-1/4">Attribute</th>
                                                            <th class="px-4 py-2 text-left font-semibold text-danger/80 border-b border-border">Previous</th>
                                                            <th class="px-4 py-2 text-left font-semibold text-emerald-600 border-b border-border">Current</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($keys as $key)
                                                            <tr class="hover:bg-muted/20 transition-colors">
                                                                <td class="px-4 py-2 font-mono text-[11px] border-b border-border text-muted-foreground">{{ str_replace('_', ' ', ucfirst($key)) }}</td>
                                                                <td class="px-4 py-2 border-b border-border font-medium text-danger/70 italic">
                                                                    {{ is_array($activity->properties['old'][$key] ?? null) ? json_encode($activity->properties['old'][$key]) : ($activity->properties['old'][$key] ?? 'N/A') }}
                                                                </td>
                                                                <td class="px-4 py-2 border-b border-border font-bold text-emerald-600">
                                                                    {{ is_array($activity->properties['new'][$key] ?? null) ? json_encode($activity->properties['new'][$key]) : ($activity->properties['new'][$key] ?? 'N/A') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Extra Context (Roles, Permissions, affected_ids) --}}
                                    <div class="space-y-4">
                                        @foreach(['affected_users' => 'Users Affected', 'roles' => 'Roles assigned', 'permissions' => 'Permissions granted', 'old_roles' => 'Roles (Before)', 'new_roles' => 'Roles (After)', 'old_permissions' => 'Permissions (Before)', 'new_permissions' => 'Permissions (After)', 'affected_ids' => 'Batch Affected IDs'] as $pKey => $pLabel)
                                            @if(isset($activity->properties[$pKey]))
                                                <div class="rounded-xl border border-border bg-card p-4 shadow-sm">
                                                    <div class="mb-2 text-[10px] font-bold uppercase tracking-widest text-muted-foreground">{{ $pLabel }}</div>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @php 
                                                            $items = is_array($activity->properties[$pKey] ?? null) ? $activity->properties[$pKey] : [$activity->properties[$pKey] ?? null]; 
                                                        @endphp
                                                        @foreach($items as $item)
                                                            @if($item !== null)
                                                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold {{ str_contains($pKey, 'old') ? 'bg-danger/10 text-danger border border-danger/20' : 'bg-primary/10 text-primary border border-primary/20' }}">
                                                                    {{ is_array($item) ? json_encode($item) : $item }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        
                                        {{-- General Metadata --}}
                                        <div class="rounded-xl border border-border bg-card p-4 shadow-sm">
                                            <div class="mb-2 text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Technical Context</div>
                                            <div class="space-y-1.5">
                                                <div class="flex justify-between text-[10px]">
                                                    <span class="text-muted-foreground">Subject Type:</span>
                                                    <span class="font-mono">{{ $activity->subject_type ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between text-[10px]">
                                                    <span class="text-muted-foreground">Subject ID:</span>
                                                    <span class="font-mono text-primary font-bold">{{ $activity->subject_id ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between text-[10px]">
                                                    <span class="text-muted-foreground">Browser:</span>
                                                    <span class="font-bold text-primary">{{ $activity->browser ?? 'Unknown' }}</span>
                                                </div>
                                                <div class="flex justify-between text-[10px]">
                                                    <span class="text-muted-foreground">Platform:</span>
                                                    <span class="font-bold text-primary">{{ $activity->platform ?? 'Unknown' }}</span>
                                                </div>
                                                <div class="flex flex-col gap-1 mt-2">
                                                    <span class="text-muted-foreground text-[10px]">User Agent:</span>
                                                    <span class="text-[9px] text-muted-foreground/60 leading-tight italic break-all">{{ $activity->user_agent }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <div class="rounded-xl border border-border bg-[#0f172a] shadow-inner overflow-hidden flex flex-col h-full">
                                        <div class="flex items-center justify-between border-b border-white/5 bg-white/5 px-4 py-2">
                                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Complete JSON Activity Data</span>
                                            <button 
                                                type="button"
                                                @click="
                                                    navigator.clipboard.writeText($el.parentElement.nextElementSibling.innerText.trim());
                                                    window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'success', title: 'Copied', description: 'Activity JSON copied to clipboard' }}));
                                                "
                                                class="text-[9px] font-bold text-primary hover:text-primary-foreground/90 bg-primary/20 hover:bg-primary px-2 py-1 rounded transition-all uppercase tracking-tight"
                                            >
                                                Copy Full JSON
                                            </button>
                                        </div>
                                        <div class="flex-1 max-h-[400px] overflow-auto custom-scrollbar p-4">
                                            <pre class="text-[11px] leading-relaxed text-emerald-400 font-mono italic whitespace-pre-wrap"><code>{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
            @empty
                <tr>
                    <td colspan="7">
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
    <nav class="flex items-center gap-2 pagination">
        @if ($activities->onFirstPage())
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </span>
        @else
            <a href="{{ $activities->previousPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Previous
            </a>
        @endif

        @if ($activities->hasMorePages())
            <a href="{{ $activities->nextPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            </span>
        @endif
    </nav>
</div>
