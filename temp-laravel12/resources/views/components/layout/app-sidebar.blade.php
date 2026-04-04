@php
    $sections = config('enterprise-ui.sidebar', []);
    $currentUser = auth()->user();

    $iconMap = [
        'layout-dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="3" width="7" height="8" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="15" width="7" height="6" rx="1"/></svg>',
        'file-text' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>',
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'shopping-bag' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
        'boxes' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="m3 8 9 5 9-5"/><path d="M12 13v8"/></svg>',
        'receipt' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 3v18l2-1.5L8 21l2-1.5L12 21l2-1.5L16 21l2-1.5L20 21V3Z"/><path d="M8 7h8"/><path d="M8 11h8"/><path d="M8 15h5"/></svg>',
        'shield' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>',
        'settings' => '<svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 2v2.2"/><path d="M12 19.8V22"/><path d="m4.93 4.93 1.56 1.56"/><path d="m17.51 17.51 1.56 1.56"/><path d="M2 12h2.2"/><path d="M19.8 12H22"/><path d="m4.93 19.07 1.56-1.56"/><path d="m17.51 6.49 1.56-1.56"/><circle cx="12" cy="12" r="3.5"/></svg>',
    ];
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 flex flex-col border-r border-sidebar-border/60 bg-sidebar/95 shadow-2xl backdrop-blur-2xl transition-all duration-300 md:translate-x-0"
    :class="{
        'w-72': !sidebarCollapsed,
        'w-[4.5rem]': sidebarCollapsed,
        '-translate-x-full': !mobileMenuOpen && window.innerWidth < 768,
        'translate-x-0': mobileMenuOpen && window.innerWidth < 768
    }"
>
    <div class="flex h-20 items-center border-b border-sidebar-border/60 px-5">
        <a href="/dashboard" class="flex w-full items-center gap-3.5" :class="sidebarCollapsed ? 'justify-center' : ''">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-primary via-sky-500 to-indigo-600 text-white shadow-lg shadow-primary/25">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3"/>
                </svg>
            </div>
            <div x-show="!sidebarCollapsed" class="overflow-hidden">
                <div class="font-heading text-lg font-bold leading-none text-foreground">{{ config('enterprise-ui.workspace_name', 'Workspace') }}</div>
                <div class="mt-1 text-[10px] font-extrabold uppercase tracking-[0.25em] text-muted-foreground">Starter Shell</div>
            </div>
        </a>
    </div>

    <div class="flex-1 space-y-8 overflow-y-auto px-3 py-6">
        @foreach($sections as $section)
            <div class="space-y-2">
                <div class="px-3" x-show="!sidebarCollapsed">
                    <div class="text-[10px] font-extrabold uppercase tracking-widest text-muted-foreground/70">{{ $section['label'] }}</div>
                </div>

                <div class="space-y-1">
                    @foreach($section['items'] as $item)
                        @continue(isset($item['permission']) && $currentUser && !$currentUser->hasPermission($item['permission']))
                        <x-layout.nav-link
                            :title="$item['title']"
                            :url="isset($item['route']) ? route($item['route']) : ($item['url'] ?? '#')"
                            :active="request()->routeIs($item['route'] ?? '__never__') || request()->is($item['pattern'] ?? '__never__')"
                            :icon="$iconMap[$item['icon']] ?? $iconMap['layout-dashboard']"
                            :badge="$item['badge'] ?? null"
                        />
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</aside>
