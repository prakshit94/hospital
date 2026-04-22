@php
    $sections = config('enterprise-ui.sidebar', []);
    $currentUser = auth()->user();

    $iconMap = [
        'layout-dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="3" width="7" height="8" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="15" width="7" height="6" rx="1"/></svg>',
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>',
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'user-md' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="m14 10 2 2 4-4"/></svg>',
        'stethoscope' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4.8 2.3A.3.3 0 1 0 5 2a.3.3 0 0 0-.2.3Z"/><path d="M10 2v2a5 5 0 0 1-10 0V2"/><path d="M7 21a2 2 0 0 0 4 0v-5a2 2 0 0 0-4 0Z"/><path d="M11 21h10"/><path d="M22 16a2 2 0 1 1-4 0V8a6 6 0 0 0-12 0v2"/></svg>',
        'bed' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>',
        'pills' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/></svg>',
        'microscope' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 22a7 7 0 1 0 0-14h-1"/><path d="M9 14h2"/><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"/><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"/></svg>',
        'activity' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
        'clipboard-list' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>',
        'file-medical' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6"/><path d="M12 12v6"/></svg>',
        'credit-card' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
        'settings' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
        'shield' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>',
        'box' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>',
        'layers' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.27a1 1 0 0 0 0 1.83l8.57 4.09a2 2 0 0 0 1.66 0l8.57-4.09a1 1 0 0 0 0-1.83Z"/><path d="m2 12 10 4.76 10-4.76"/><path d="m2 16 10 4.76 10-4.76"/></svg>',
        'file-text' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>',
        'tool' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        'smartphone' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>',
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
                    <path d="M21 10h-1V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6H3a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z"/>
                    <path d="M12 6v4"/>
                    <path d="M10 8h4"/>
                    <path d="M10 22V15a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7"/>
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
