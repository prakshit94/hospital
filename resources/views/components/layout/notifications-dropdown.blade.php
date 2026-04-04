@php
    $activities = \App\Models\ActivityLog::query()
        ->latest()
        ->take(5)
        ->get();

    $lastSeen = auth()->user()->notifications_read_at ?? now()->subYears(10);
    $unreadCount = $activities->filter(fn($a) => $a->created_at->gt($lastSeen))->count();

    $notifications = $activities->map(function ($activity) use ($lastSeen) {
        $tone = 'muted';
        if (str_contains($activity->action, 'created')) $tone = 'success';
        if (str_contains($activity->action, 'updated')) $tone = 'primary';
        if (str_contains($activity->action, 'deleted')) $tone = 'danger';
        if (str_contains($activity->action, 'status')) $tone = 'primary';

        return [
            'id' => $activity->id,
            'title' => ucwords(str_replace(['.', '_'], ' ', $activity->action)),
            'meta' => $activity->description,
            'time' => $activity->created_at?->diffForHumans() ?? 'Unknown',
            'tone' => $tone,
            'is_unread' => $activity->created_at->gt($lastSeen),
        ];
    });
@endphp

<div x-data="{ 
    open: false,
    marking: false,
    unreadCount: {{ $unreadCount }},
    async markAllRead() {
        this.marking = true;
        try {
            const response = await fetch('{{ route('notifications.mark-as-read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                this.unreadCount = 0;
                this.$dispatch('mark-all-read');
                window.dispatchEvent(new CustomEvent('toast-notify', {
                    detail: {
                        type: 'success',
                        title: 'Success',
                        message: 'All notifications marked as read'
                    }
                }));
            }
        } finally {
            this.marking = false;
        }
    }
}" class="relative">
    <button
        type="button"
        @click="open = !open"
        class="group relative flex h-11 w-11 items-center justify-center rounded-[1rem] border border-border bg-card text-muted-foreground shadow-[0_14px_32px_-26px_rgba(15,23,42,0.18)] transition duration-300 hover:bg-secondary hover:text-primary"
        aria-label="Open notifications"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"/>
            <path d="M9 17a3 3 0 0 0 6 0"/>
        </svg>
        <span x-show="unreadCount > 0" class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white shadow-[0_4px_12px_-4px_rgba(244,63,94,0.45)] transition-transform duration-300 group-hover:scale-110">
            <span x-text="unreadCount"></span>
        </span>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition
        class="absolute right-0 z-50 mt-3 w-[20rem] overflow-hidden rounded-[1.4rem] border border-border bg-popover p-3 shadow-[0_24px_50px_-30px_rgba(15,23,42,0.3)]"
    >
        <div class="mb-3 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-foreground">Notifications</div>
                <div class="mt-1 text-xs text-muted-foreground">Latest alerts and updates.</div>
            </div>
            <button 
                type="button" 
                @click="markAllRead()" 
                x-show="unreadCount > 0"
                class="rounded-lg bg-secondary/80 px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-primary hover:bg-secondary transition-all disabled:opacity-50"
                :disabled="marking"
            >
                <span x-show="!marking">Mark All Read</span>
                <span x-show="marking">Marking...</span>
            </button>
        </div>

        <div class="space-y-2">
            @forelse($notifications as $notification)
                <button
                    x-data="{ isUnread: {{ $notification['is_unread'] ? 'true' : 'false' }} }"
                    @mark-all-read.window="isUnread = false"
                    :class="isUnread ? 'border-primary/20 bg-primary/[0.04] hover:bg-primary/[0.08]' : 'border-border bg-card/60 hover:bg-secondary'"
                    type="button"
                    class="relative block w-full rounded-[1.25rem] border px-4 py-3.5 text-left transition duration-200 {{ $notification['is_unread'] ? 'border-primary/20 bg-primary/[0.04]' : 'border-border bg-card/60' }}"
                >
                    <div x-show="isUnread" class="absolute -left-1 top-1/2 flex h-2.5 w-2.5 -translate-y-1/2 items-center justify-center rounded-full bg-primary ring-4 ring-background" x-transition></div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-sm font-bold tracking-tight text-foreground">{{ $notification['title'] }}</div>
                            <div class="mt-1 text-xs leading-relaxed text-muted-foreground line-clamp-2">
                                {{ $notification['meta'] }}
                                @php
                                    $act = $activities->firstWhere('id', $notification['id']);
                                    $changedCount = isset($act->properties['old']) ? count($act->properties['old']) : 0;
                                @endphp
                                @if($changedCount > 0)
                                    <div class="mt-1.5 flex items-center gap-1.5 font-mono text-[10px] uppercase tracking-wider">
                                        <span class="rounded bg-primary/10 px-1.5 py-0.5 font-bold text-primary italic">
                                            {{ $changedCount }} {{ \Illuminate\Support\Str::plural('field', $changedCount) }} updated
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ $notification['tone'] === 'success' ? 'bg-emerald-500' : ($notification['tone'] === 'primary' ? 'bg-primary' : ($notification['tone'] === 'danger' ? 'bg-rose-500' : 'bg-slate-400')) }}"></span>
                    </div>
                    <div class="mt-2.5 text-[10px] font-bold uppercase tracking-widest text-muted-foreground/60">{{ $notification['time'] }}</div>
                </button>
            @empty
                <div class="py-12 text-center">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-secondary/50 text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9m4 13a3 3 0 0 0 6 0"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-muted-foreground">No recent notifications</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
