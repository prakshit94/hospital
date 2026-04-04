@php
    /** @var \App\Models\User|null $user */
    $user = auth()->user();
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        @click="open = !open"
        class="flex items-center gap-3 rounded-[1.35rem] border border-white/35 bg-white/50 px-3 py-2.5 shadow-[0_20px_45px_-28px_rgba(15,23,42,0.35)] transition duration-300 hover:bg-white/72 dark:border-white/8 dark:bg-white/7 dark:hover:bg-white/10"
    >
        <div class="flex size-10 items-center justify-center rounded-[1.1rem] border border-white/25 bg-[linear-gradient(135deg,color-mix(in_oklab,var(--primary)_14%,white_86%),rgba(255,255,255,0.64))] text-sm font-bold text-primary dark:border-white/8 dark:bg-[linear-gradient(135deg,color-mix(in_oklab,var(--primary)_18%,transparent),rgba(255,255,255,0.06))]">
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
        class="absolute right-0 z-50 mt-3 w-60 overflow-hidden rounded-[1.8rem] border border-white/35 bg-popover/92 p-2 shadow-[0_26px_70px_-30px_rgba(15,23,42,0.45)] backdrop-blur-2xl dark:border-white/8"
    >
        <a href="{{ route('profile.edit') }}" class="block rounded-[1.2rem] px-4 py-3 text-sm text-foreground transition duration-300 hover:bg-white/55 hover:text-primary dark:hover:bg-white/8">
            Profile
        </a>
        <a href="{{ route('dashboard') }}" class="block rounded-[1.2rem] px-4 py-3 text-sm text-foreground transition duration-300 hover:bg-white/55 hover:text-primary dark:hover:bg-white/8">
            Dashboard
        </a>
        @if($user?->hasPermission('reports.view'))
            <a href="{{ route('reports.index') }}" class="block rounded-[1.2rem] px-4 py-3 text-sm text-foreground transition duration-300 hover:bg-white/55 hover:text-primary dark:hover:bg-white/8">
                Reports
            </a>
        @endif
        @if($user?->hasPermission('activities.view'))
            <a href="{{ route('activity-logs.index') }}" class="block rounded-[1.2rem] px-4 py-3 text-sm text-foreground transition duration-300 hover:bg-white/55 hover:text-primary dark:hover:bg-white/8">
                Activity Logs
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full rounded-[1.2rem] px-4 py-3 text-left text-sm text-foreground transition duration-300 hover:bg-white/55 hover:text-primary dark:hover:bg-white/8">
                Sign out
            </button>
        </form>
    </div>
</div>
