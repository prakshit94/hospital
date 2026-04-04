@php
    $sections = config('enterprise-ui.sidebar', []);
    $currentUser = auth()->user();

    $iconMap = [
        'layout-dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="3" width="7" height="8" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="15" width="7" height="6" rx="1"/></svg>',
        'file-text' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>',
        'shopping-cart' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>',
        'package' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.27 6.96 8.73 5.04 8.73-5.04"/><path d="M12 22.08V12"/></svg>',
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'tag' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>',
        'layers' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.27a1 1 0 0 0 0 1.83l8.57 4.09a2 2 0 0 0 1.66 0l8.57-4.09a1 1 0 0 0 0-1.83Z"/><path d="m2 12 10 4.76 10-4.76"/><path d="m2 16 10 4.76 10-4.76"/></svg>',
        'settings' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>',
        'tool' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 2v2.2"/><path d="M12 19.8V22"/><path d="m4.93 4.93 1.56 1.56"/><path d="m17.51 17.51 1.56 1.56"/><path d="M2 12h2.2"/><path d="M19.8 12H22"/><path d="m4.93 19.07 1.56-1.56"/><path d="m17.51 6.49 1.56-1.56"/><circle cx="12" cy="12" r="3.5"/></svg>',
        'credit-card' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
        'truck' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><path d="M18 18h1a1 1 0 0 0 1-1v-6.34a2 2 0 0 0-.59-1.41l-2.42-2.42a2 2 0 0 0-1.41-.59H14"/><circle cx="17" cy="18" r="2"/></svg>',
        'percent' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><line x1="19" x2="5" y1="5" y2="19"/><circle cx="7.5" cy="7.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/></svg>',
        'box' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>',
        'monitor' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>',
        'shield' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>',
    ];
@endphp

<aside
    x-cloak
    class="fixed inset-y-0 left-0 z-50 m-3 flex flex-col overflow-visible rounded-[1.75rem] border border-sidebar-border bg-sidebar shadow-[0_24px_54px_-34px_rgba(15,23,42,0.28)] transition-all duration-300 lg:translate-x-0"
    :class="{
        'w-[min(19rem,calc(100vw-1.5rem))] lg:w-72': !isDesktop || !sidebarCollapsed,
        'lg:w-[4.75rem]': isDesktop && sidebarCollapsed,
        '-translate-x-full': !isDesktop && !mobileMenuOpen,
        'translate-x-0': isDesktop || mobileMenuOpen
    }"
>
    <div class="relative z-10 flex h-24 items-center border-b border-sidebar-border/40 px-6">
        <a href="/dashboard" class="flex min-w-0 flex-1 items-center gap-4" :class="sidebarCollapsed ? 'justify-center' : ''" @click="closeMobileMenu()">
            <div class="flex h-12 w-12 items-center justify-center rounded-[1rem] bg-primary text-white shadow-[0_16px_32px_-20px_color-mix(in_oklab,var(--primary)_60%,transparent)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1">
                    <path d="M3 11.5 12 4l9 7.5"/>
                    <path d="M5 10.5V20h14v-9.5"/>
                    <path d="M9 20v-5h6v5"/>
                </svg>
            </div>
            <div x-show="!sidebarCollapsed" class="overflow-hidden space-y-0.5">
                <div class="font-heading text-xl font-black leading-none tracking-tight text-foreground">{{ config('enterprise-ui.workspace_name', 'Workspace') }}</div>
            </div>
        </a>
        <button
            type="button"
            @click="closeMobileMenu()"
            class="ml-auto flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-secondary text-muted-foreground transition hover:text-foreground lg:hidden"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="relative z-10 flex-1 space-y-8 px-4 py-6" :class="isDesktop && sidebarCollapsed ? 'overflow-visible' : 'overflow-y-auto'">
        @foreach($sections as $section)
            <div class="space-y-4">
                <div class="px-4" x-show="!sidebarCollapsed">
                    <div class="text-[9.5px] font-black uppercase tracking-[0.4em] text-muted-foreground/40">{{ $section['label'] }}</div>
                </div>

                <div class="space-y-1">
                    @foreach($section['items'] as $item)
                        @continue(isset($item['permission']) && $currentUser && !$currentUser->hasPermission($item['permission']))

                        @if(isset($item['children']) && count($item['children']) > 0)
                            <x-layout.nav-collapsible
                                :title="$item['title']"
                                :icon="$iconMap[$item['icon']] ?? $iconMap['layout-dashboard']"
                                :items="collect($item['children'])->map(function($child) use ($currentUser, $iconMap) {
                                    if (isset($child['permission']) && $currentUser && !$currentUser->hasPermission($child['permission'])) {
                                        return null;
                                    }
                                    $child['url'] = isset($child['route']) ? route($child['route']) : ($child['url'] ?? '#');
                                    $child['icon_svg'] = $iconMap[$child['icon'] ?? ''] ?? null;
                                    return $child;
                                })->filter()->toArray()"
                                :active="collect($item['children'])->pluck('pattern')->contains(fn($p) => request()->is($p))"
                            />
                        @else
                            <x-layout.nav-link
                                :title="$item['title']"
                                :url="isset($item['route']) ? route($item['route']) : ($item['url'] ?? '#')"
                                :active="request()->routeIs($item['route'] ?? '__never__') || request()->is($item['pattern'] ?? '__never__')"
                                :icon="$iconMap[$item['icon']] ?? $iconMap['layout-dashboard']"
                                :badge="$item['badge'] ?? null"
                            />
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</aside>
