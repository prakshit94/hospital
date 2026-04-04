@props(['pageTitle' => null, 'hideSidebarToggle' => false])

@php
    $title = $pageTitle ?: config('enterprise-ui.app_name', config('app.name'));
    $workspace = config('enterprise-ui.workspace_name', 'Console');
@endphp

<header class="sticky top-0 z-40 mx-4 mt-4 rounded-[1.5rem] border border-border bg-card px-4 py-3 shadow-[0_18px_44px_-30px_rgba(15,23,42,0.18)] md:mx-6 md:mt-6 md:px-6">
    <div class="flex flex-wrap items-center gap-3 lg:flex-nowrap">
        <div class="flex min-w-0 flex-1 items-center gap-3 md:gap-6">
            @unless($hideSidebarToggle)
                <button
                    type="button"
                    @click="toggleNavigation()"
                    class="flex rounded-2xl border border-border bg-secondary p-3 text-muted-foreground transition duration-300 hover:bg-accent hover:text-primary"
                    aria-label="Toggle navigation"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                    </svg>
                </button>
                <div class="hidden h-10 w-px bg-gradient-to-b from-transparent via-border/50 to-transparent md:block"></div>
            @endunless

            <div class="min-w-0">
                <div class="hidden items-center gap-3 md:flex">
                    <span class="rounded-xl border border-border bg-secondary px-4 py-2.5 text-[10px] font-bold uppercase tracking-[0.18em] text-muted-foreground">
                        {{ $workspace }}
                    </span>
                    <span class="truncate text-sm font-semibold text-foreground">{{ $title }}</span>
                </div>
                <div class="md:hidden">
                    <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-muted-foreground">{{ $workspace }}</div>
                    <div class="mt-1 truncate font-heading text-lg font-black text-foreground">{{ $title }}</div>
                </div>
            </div>
        </div>

        <div class="order-3 w-full lg:order-2 lg:max-w-xl lg:flex-1">
            <div class="group relative w-full">
                <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-muted-foreground/40 transition-colors duration-300 group-focus-within:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </div>
                <input
                    type="text"
                    data-command-search
                    placeholder="Search users, roles, permissions, reports..."
                    class="command-input py-3"
                >
                <div class="command-kbd">
                    Ctrl K
                </div>
            </div>
        </div>

        <div class="order-2 flex items-center gap-2 sm:gap-3 lg:order-3">
            <div class="hidden rounded-[1rem] border border-border bg-secondary px-3 py-2 text-right lg:block">
                <div class="text-[10px] font-bold uppercase tracking-[0.14em] text-muted-foreground">Active Tab</div>
                <div class="mt-1 max-w-[10rem] truncate text-sm font-semibold text-foreground">{{ $title }}</div>
            </div>

            <x-layout.notifications-dropdown />

            <x-layout.inbox-dropdown />

            <x-layout.settings-dropdown />

            <div class="hidden h-8 w-px bg-border/40 md:block"></div>

            <x-layout.user-dropdown />
        </div>
    </div>
</header>
