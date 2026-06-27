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
    <div class="flex items-center justify-between gap-4">

        {{-- LEFT: Toggle + Breadcrumbs --}}
        <div class="flex min-w-0 flex-1 items-center gap-3 md:gap-4">
            @unless($hideSidebarToggle)
                <button
                    type="button"
                    @click="toggleNavigation()"
                    class="flex shrink-0 rounded-2xl border border-border bg-secondary p-3 text-muted-foreground transition duration-300 hover:bg-accent hover:text-primary"
                    aria-label="Toggle navigation"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                    </svg>
                </button>
            @endunless

            <nav class="flex min-w-0 items-center gap-2 overflow-x-auto no-scrollbar py-1">
                @foreach($breadcrumbs as $index => $bc)
                    <div class="flex shrink-0 items-center gap-2">
                        @if($index > 0)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-muted-foreground/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        @endif

                        @if($index === 0)
                            <a href="{{ $bc['url'] }}" class="rounded-xl border border-border bg-secondary/80 px-3.5 py-2 text-[10px] font-black uppercase tracking-[0.18em] text-muted-foreground transition duration-300 hover:bg-secondary hover:text-primary">
                                {{ $bc['name'] }}
                            </a>
                        @else
                            <a href="{{ $bc['url'] }}" class="truncate text-sm font-semibold text-foreground transition duration-300 hover:text-primary">
                                {{ $bc['name'] }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </nav>
        </div>

        {{-- RIGHT: Company Switcher + Icons --}}
        <div class="flex shrink-0 items-center gap-2 sm:gap-3">

            {{-- Company Switcher --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-2 rounded-xl border border-border bg-secondary/50 px-3 py-2 text-xs font-bold text-foreground transition duration-300 hover:bg-secondary">
                    <div class="flex h-5 w-5 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M3 21h18"/><path d="M3 7v1a3 3 0 0 0 6 0V7"/><path d="M9 7v1a3 3 0 0 0 6 0V7"/><path d="M15 7v1a3 3 0 0 0 6 0V7"/><path d="M19 21V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v14"/>
                        </svg>
                    </div>
                    <span class="hidden max-w-[120px] truncate sm:inline">{{ session('current_company_name', 'All Companies') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-muted-foreground transition-transform duration-300" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-2xl border border-border bg-card p-2 shadow-2xl">
                    <div class="mb-2 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-muted-foreground">Switch Context</div>

                    <form action="{{ route('companies.switch') }}" method="POST">
                        @csrf
                        <button type="submit" name="company_id" value="all"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-sm transition duration-300 hover:bg-secondary {{ !session('current_company_id') ? 'bg-secondary text-primary font-bold' : 'text-foreground' }}">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-secondary text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M3 21h18"/><path d="M3 7v1a3 3 0 0 0 6 0V7"/><path d="M19 21V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v14"/>
                                </svg>
                            </div>
                            All Companies
                        </button>

                        @foreach($globalCompanies as $company)
                            <button type="submit" name="company_id" value="{{ $company->id }}"
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-sm transition duration-300 hover:bg-secondary {{ session('current_company_id') == $company->id ? 'bg-secondary text-primary font-bold' : 'text-foreground' }}">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                    <span class="text-[10px] font-black">{{ $company->code ?: strtoupper(substr($company->name, 0, 2)) }}</span>
                                </div>
                                <span class="truncate">{{ $company->name }}</span>
                            </button>
                        @endforeach
                    </form>

                    <div class="mt-2 border-t border-border pt-2">
                        <a href="{{ route('companies.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-muted-foreground transition duration-300 hover:bg-secondary hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                            </svg>
                            Manage Companies
                        </a>
                    </div>
                </div>
            </div>

            <x-layout.notifications-dropdown />

            <x-layout.settings-dropdown />

            <div class="hidden h-8 w-px bg-border/40 md:block"></div>

            <x-layout.user-dropdown />
        </div>

    </div>
</header>
