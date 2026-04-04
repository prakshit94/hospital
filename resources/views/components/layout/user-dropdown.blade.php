@php
    /** @var \App\Models\User|null $user */
    $user = auth()->user();
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        @click="open = !open"
        class="flex items-center gap-3 rounded-[1.1rem] border border-border bg-card px-3 py-2.5 shadow-[0_14px_32px_-26px_rgba(15,23,42,0.18)] transition duration-300 hover:bg-secondary"
    >
        <div class="flex size-10 items-center justify-center rounded-[0.9rem] bg-secondary text-sm font-bold text-primary">
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
        class="absolute right-0 z-50 mt-3 w-64 overflow-hidden rounded-[1.4rem] border border-border bg-popover p-2 shadow-[0_24px_50px_-30px_rgba(15,23,42,0.3)]"
    >
        <div class="mb-2 rounded-[1rem] border border-border bg-secondary px-4 py-3">
            <div class="text-sm font-semibold text-foreground">{{ $user?->email ?? 'No email' }}</div>
            <div class="mt-1 text-[11px] font-bold uppercase tracking-[0.14em] text-muted-foreground">Account</div>
        </div>
        <a href="{{ route('profile.edit') }}" class="block rounded-[1rem] px-4 py-3 text-sm text-foreground transition duration-300 hover:bg-secondary hover:text-primary">
            Profile settings
        </a>
        <a href="{{ route('dashboard') }}" class="block rounded-[1rem] px-4 py-3 text-sm text-foreground transition duration-300 hover:bg-secondary hover:text-primary">
            Dashboard
        </a>
        @if($user?->hasPermission('reports.view'))
            <a href="{{ route('reports.index') }}" class="block rounded-[1rem] px-4 py-3 text-sm text-foreground transition duration-300 hover:bg-secondary hover:text-primary">
                Reports
            </a>
        @endif
        @if($user?->hasPermission('activities.view'))
            <a href="{{ route('activity-logs.index') }}" class="block rounded-[1rem] px-4 py-3 text-sm text-foreground transition duration-300 hover:bg-secondary hover:text-primary">
                Activity Logs
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full rounded-[1rem] px-4 py-3 text-left text-sm text-foreground transition duration-300 hover:bg-secondary hover:text-primary">
                Sign out
            </button>
        </form>
    </div>
</div>
