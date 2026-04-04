@props(['pageTitle' => null, 'hideSidebarToggle' => false])

@php
    $sidebar = config('enterprise-ui.sidebar', []);
    $breadcrumbs = [];
    $currRoute = request()->route()?->getName();
    $currPath = request()->path();
    
    // Find active breadcrumbs from sidebar config
    $found = false;
    foreach($sidebar as $section) {
        foreach($section['items'] as $item) {
            $isItemMatch = (isset($item['route']) && $item['route'] === $currRoute) || 
                          (isset($item['pattern']) && request()->is($item['pattern']));
            
            if($isItemMatch) {
                $breadcrumbs[] = ['name' => $item['title'], 'url' => isset($item['route']) ? route($item['route']) : url($item['url'] ?? '#'), 'id' => 'root'];
                $found = true;
                break 2;
            }

            if(isset($item['children'])) {
                foreach($item['children'] as $child) {
                    $isChildMatch = (isset($child['route']) && $child['route'] === $currRoute) || 
                                   (isset($child['pattern']) && request()->is($child['pattern']));
                    
                    if($isChildMatch) {
                        $breadcrumbs[] = ['name' => $item['title'], 'url' => '#', 'id' => 'parent'];
                        $breadcrumbs[] = ['name' => $child['title'], 'url' => isset($child['route']) ? route($child['route']) : url($child['url'] ?? '#'), 'id' => 'child'];
                        $found = true;
                        break 3;
                    }
                }
            }
        }
    }

    // Fallback to segments if route not in sidebar
    if(!$found) {
        $segments = request()->segments();
        foreach($segments as $segment) {
            if($segment === 'dashboard') continue;
            $breadcrumbs[] = [
                'name' => ucwords(str_replace(['-', '_'], ' ', $segment)),
                'url' => '#',
                'id' => 'segment'
            ];
        }
    }

    // Ensure we have at least one segment (fallback to workspace if empty)
    if(empty($breadcrumbs)) {
        $breadcrumbs[] = ['name' => config('enterprise-ui.workspace_name', 'Security Console'), 'url' => route('dashboard'), 'id' => 'fallback'];
    }

    $title = $pageTitle ?: ($breadcrumbs ? end($breadcrumbs)['name'] : config('enterprise-ui.app_name', config('app.name')));
@endphp

<header class="sticky top-0 z-40 mx-4 mt-4 rounded-[1.5rem] border border-border bg-card px-4 py-3 shadow-[0_18px_44px_-30px_rgba(15,23,42,0.18)] md:mx-6 md:mt-6 md:px-6">
    <div class="flex flex-wrap items-center gap-3 lg:flex-nowrap">
        <div class="flex min-w-0 shrink-0 items-center gap-3 md:gap-6">
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
            @endunless

            <nav class="flex items-center gap-2 overflow-hidden overflow-x-auto no-scrollbar py-1">
                @foreach($breadcrumbs as $index => $bc)
                    <div class="flex items-center gap-2 shrink-0">
                        @if($index > 0)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-muted-foreground/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        @endif
                        
                        @if($index === 0)
                            <a href="{{ $bc['url'] }}" class="rounded-xl border border-border bg-secondary/80 px-3.5 py-2 text-[10px] font-black uppercase tracking-[0.18em] text-muted-foreground transition hover:bg-secondary hover:text-primary">
                                {{ $bc['name'] }}
                            </a>
                        @else
                            <a href="{{ $bc['url'] }}" class="truncate text-sm font-semibold text-foreground transition hover:text-primary">
                                {{ $bc['name'] }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </nav>
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

            <x-layout.notifications-dropdown />

            <x-layout.inbox-dropdown />

            <x-layout.settings-dropdown />

            <div class="hidden h-8 w-px bg-border/40 md:block"></div>

            <x-layout.user-dropdown />
        </div>
    </div>
</header>
