@php
    $messages = [
        ['title' => 'Security review', 'meta' => 'Permission checklist needs approval', 'time' => '5 min ago'],
        ['title' => 'Operations note', 'meta' => 'Daily report is ready to review', 'time' => '19 min ago'],
        ['title' => 'Team update', 'meta' => 'New workflow rules were shared', 'time' => '1 hr ago'],
    ];
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        @click="open = !open"
        class="group relative flex h-11 w-11 items-center justify-center rounded-[1rem] border border-border bg-card text-muted-foreground shadow-[0_14px_32px_-26px_rgba(15,23,42,0.18)] transition duration-300 hover:bg-secondary hover:text-primary"
        aria-label="Open inbox"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 12.5V8a2 2 0 0 0-2-2h-3l-2-3H9L7 6H4a2 2 0 0 0-2 2v4.5"/>
            <path d="M2 12.5h5l2 3h6l2-3h5"/>
            <path d="M5 18h14"/>
        </svg>
        <span class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-[10px] font-black text-white shadow-[0_4px_12px_-4px_color-mix(in_oklab,var(--primary)_50%,transparent)] transition-transform duration-300 group-hover:scale-110">
            {{ count($messages) }}
        </span>
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
                <div class="text-sm font-semibold text-foreground">Inbox</div>
                <div class="mt-1 text-xs text-muted-foreground">Messages and internal notes.</div>
            </div>
            <span class="ui-chip">3 Items</span>
        </div>

        <div class="space-y-2">
            @foreach($messages as $message)
                <button
                    type="button"
                    class="block w-full rounded-[1rem] border border-border bg-card px-4 py-3 text-left transition hover:bg-secondary"
                >
                    <div class="text-sm font-semibold text-foreground">{{ $message['title'] }}</div>
                    <div class="mt-1 text-xs text-muted-foreground">{{ $message['meta'] }}</div>
                    <div class="mt-2 text-[11px] font-medium text-muted-foreground">{{ $message['time'] }}</div>
                </button>
            @endforeach
        </div>
    </div>
</div>
