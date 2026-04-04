@php
    $notifications = [
        ['title' => 'Role updated', 'meta' => 'Admin role permissions changed', 'time' => '2 min ago', 'tone' => 'primary'],
        ['title' => 'New user created', 'meta' => 'A new account was provisioned', 'time' => '12 min ago', 'tone' => 'success'],
        ['title' => 'Export ready', 'meta' => 'Latest audit export completed', 'time' => '28 min ago', 'tone' => 'muted'],
    ];
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        @click="open = !open"
        class="relative flex h-11 w-11 items-center justify-center rounded-[1rem] border border-border bg-card text-muted-foreground shadow-[0_14px_32px_-26px_rgba(15,23,42,0.18)] transition duration-300 hover:bg-secondary hover:text-primary"
        aria-label="Open notifications"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"/>
            <path d="M9 17a3 3 0 0 0 6 0"/>
        </svg>
        <span class="absolute right-2 top-2 flex h-2.5 w-2.5 rounded-full bg-rose-500"></span>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition
        class="absolute right-0 z-50 mt-3 w-[19rem] overflow-hidden rounded-[1.4rem] border border-border bg-popover p-3 shadow-[0_24px_50px_-30px_rgba(15,23,42,0.3)]"
    >
        <div class="mb-3 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-foreground">Notifications</div>
                <div class="mt-1 text-xs text-muted-foreground">Latest alerts and updates.</div>
            </div>
            <span class="ui-chip-muted">3 New</span>
        </div>

        <div class="space-y-2">
            @foreach($notifications as $notification)
                <button
                    type="button"
                    class="block w-full rounded-[1rem] border border-border bg-card px-4 py-3 text-left transition hover:bg-secondary"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-foreground">{{ $notification['title'] }}</div>
                            <div class="mt-1 text-xs text-muted-foreground">{{ $notification['meta'] }}</div>
                        </div>
                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full {{ $notification['tone'] === 'success' ? 'bg-emerald-500' : ($notification['tone'] === 'primary' ? 'bg-primary' : 'bg-slate-400') }}"></span>
                    </div>
                    <div class="mt-2 text-[11px] font-medium text-muted-foreground">{{ $notification['time'] }}</div>
                </button>
            @endforeach
        </div>
    </div>
</div>
