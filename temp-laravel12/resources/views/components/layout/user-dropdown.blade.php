@php
    /** @var \App\Models\User|null $user */
    $user = auth()->user();
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        @click="open = !open"
        class="flex items-center gap-3 rounded-2xl border border-border/60 bg-secondary/30 px-3 py-2.5 transition hover:bg-secondary/50"
    >
        <div class="flex size-10 items-center justify-center rounded-2xl bg-primary/12 text-sm font-bold text-primary">
            {{ strtoupper(substr($user?->name ?? 'User', 0, 2)) }}
        </div>
        <div class="hidden text-left md:block">
            <div class="text-sm font-semibold text-foreground">{{ $user?->name ?? 'User' }}</div>
            <div class="text-xs text-muted-foreground">{{ $user?->primaryRole()?->name ?? 'No role assigned' }}</div>
        </div>
        <svg class="size-4 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition
        class="absolute right-0 z-50 mt-3 w-56 overflow-hidden rounded-3xl border border-border/60 bg-popover/95 p-2 shadow-2xl backdrop-blur-xl"
    >
        <a href="{{ route('dashboard') }}" class="block rounded-2xl px-4 py-3 text-sm text-foreground transition hover:bg-primary/6 hover:text-primary">
            Dashboard
        </a>
        @if($user?->hasPermission('activities.view'))
            <a href="{{ route('activity-logs.index') }}" class="block rounded-2xl px-4 py-3 text-sm text-foreground transition hover:bg-primary/6 hover:text-primary">
                Activity Logs
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full rounded-2xl px-4 py-3 text-left text-sm text-foreground transition hover:bg-primary/6 hover:text-primary">
                Sign out
            </button>
        </form>
    </div>
</div>
