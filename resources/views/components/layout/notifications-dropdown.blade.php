@php
    $activities = \App\Models\ActivityLog::query()
        ->latest()
        ->take(5)
        ->get()
        ->keyBy('id'); // ⚡ optimize lookup

    $lastSeen = auth()->user()->notifications_read_at ?? now()->subYears(10);

    $notifications = $activities->map(function ($activity) use ($lastSeen) {

        $tone = match (true) {
            str_contains($activity->action, 'created') => 'success',
            str_contains($activity->action, 'updated') => 'primary',
            str_contains($activity->action, 'deleted') => 'danger',
            str_contains($activity->action, 'status') => 'primary',
            default => 'muted',
        };

        // ✅ safe access
        $old = data_get($activity, 'properties.old', []);
        $changedCount = is_countable($old) ? count($old) : 0;

        return [
            'id' => $activity->id,
            'title' => ucwords(str_replace(['.', '_'], ' ', $activity->action)),
            'meta' => $activity->description,
            'time' => $activity->created_at?->diffForHumans() ?? 'Unknown',
            'tone' => $tone,
            'is_unread' => $activity->created_at->gt($lastSeen),
            'changed_count' => $changedCount, // ✅ precomputed
        ];
    });

    $unreadCount = $notifications->where('is_unread', true)->count();
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

    <!-- 🔔 Button -->
    <button
        type="button"
        @click="open = !open"
        class="group relative flex h-11 w-11 items-center justify-center rounded-[1rem] border border-border bg-card text-muted-foreground shadow-[0_14px_32px_-26px_rgba(15,23,42,0.18)] transition duration-300 hover:bg-secondary hover:text-primary"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"/>
            <path d="M9 17a3 3 0 0 0 6 0"/>
        </svg>

        <span x-show="unreadCount > 0"
            class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white">
            <span x-text="unreadCount"></span>
        </span>
    </button>

    <!-- 📦 Dropdown -->
    <div x-show="open" x-cloak @click.away="open = false"
        class="absolute right-0 z-50 mt-3 w-[20rem] rounded-[1.4rem] border border-border bg-popover p-3 shadow-lg">

        <!-- Header -->
        <div class="mb-3 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold">Notifications</div>
                <div class="text-xs text-muted-foreground">Latest alerts and updates</div>
            </div>

            <button 
                @click="markAllRead()"
                x-show="unreadCount > 0"
                :disabled="marking"
                class="text-xs font-bold text-primary">
                <span x-show="!marking">Mark All</span>
                <span x-show="marking">...</span>
            </button>
        </div>

        <!-- Items -->
        <div class="space-y-2">
            @forelse($notifications as $notification)
                <button
                    x-data="{ isUnread: {{ $notification['is_unread'] ? 'true' : 'false' }} }"
                    @mark-all-read.window="isUnread = false"
                    class="w-full text-left rounded-xl border px-4 py-3 transition"
                    :class="isUnread ? 'bg-primary/10 border-primary/20' : 'bg-card'"
                >
                    <div class="flex justify-between">
                        <div>
                            <div class="font-bold text-sm">
                                {{ $notification['title'] }}
                            </div>

                            <div class="text-xs text-muted-foreground mt-1">
                                {{ $notification['meta'] }}

                                @if($notification['changed_count'] > 0)
                                    <div class="mt-1 text-[10px] font-mono text-primary">
                                        {{ $notification['changed_count'] }}
                                        {{ \Illuminate\Support\Str::plural('field', $notification['changed_count']) }}
                                        updated
                                    </div>
                                @endif
                            </div>
                        </div>

                        <span class="h-2 w-2 mt-1 rounded-full
                            {{ $notification['tone'] === 'success' ? 'bg-green-500' : '' }}
                            {{ $notification['tone'] === 'primary' ? 'bg-blue-500' : '' }}
                            {{ $notification['tone'] === 'danger' ? 'bg-red-500' : '' }}
                        "></span>
                    </div>

                    <div class="text-[10px] text-muted-foreground mt-2">
                        {{ $notification['time'] }}
                    </div>
                </button>
            @empty
                <div class="text-center py-10 text-muted-foreground">
                    No notifications
                </div>
            @endforelse
        </div>
    </div>
</div>