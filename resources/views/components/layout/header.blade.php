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

        <div class="order-3 w-full lg:order-2 lg:flex-1" x-data="{
            query: '',
            searching: false,
            async searchIntent() {
                // Regular command search
                console.log('Regular command search:', this.query);
            }
        }">
            <div class="group relative w-full">
                <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-muted-foreground/40 transition-colors duration-300 group-focus-within:text-emerald-500">
                    <template x-if="searching">
                         <svg class="animate-spin size-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </template>
                    <template x-if="!searching">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                    </template>
                </div>
                <input
                    type="text"
                    x-model="query"
                    @input="if (/^\d+$/.test(query)) query = query.slice(0, 10)"
                    @keydown.enter="searchIntent()"
                    data-command-search
                    placeholder="Search apps..."
                    class="command-input py-3 pl-11 focus:ring-emerald-500/20"
                    :class="searching ? 'opacity-50 pointer-events-none' : ''"
                >
                <div class="command-kbd" x-show="!query">
                    Ctrl K
                </div>
            </div>
        </div>

        <div class="order-2 flex items-center gap-2 sm:gap-3 lg:order-3">
            <!-- Company Switcher -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center gap-2 rounded-xl border border-border bg-secondary/50 px-3 py-2 text-xs font-bold text-foreground transition hover:bg-secondary">
                    <div class="flex h-5 w-5 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M3 21h18"/><path d="M3 7v1a3 3 0 0 0 6 0V7"/><path d="M9 7v1a3 3 0 0 0 6 0V7"/><path d="M15 7v1a3 3 0 0 0 6 0V7"/><path d="M19 21V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v14"/>
                        </svg>
                    </div>
                    <span class="max-w-[120px] truncate">{{ session('current_company_name', 'All Companies') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-muted-foreground transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-64 origin-top-right rounded-2xl border border-border bg-card p-2 shadow-2xl z-50">
                    <div class="mb-2 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-muted-foreground">Switch Context</div>
                    
                    <form action="{{ route('companies.switch') }}" method="POST">
                        @csrf
                        <button type="submit" name="company_id" value="all" 
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-sm transition hover:bg-secondary {{ !session('current_company_id') ? 'bg-secondary text-primary font-bold' : 'text-foreground' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M3 21h18"/><path d="M3 7v1a3 3 0 0 0 6 0V7"/><path d="M19 21V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v14"/>
                                </svg>
                            </div>
                            All Companies
                        </button>

                        @foreach($globalCompanies as $company)
                            <button type="submit" name="company_id" value="{{ $company->id }}" 
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-sm transition hover:bg-secondary {{ session('current_company_id') == $company->id ? 'bg-secondary text-primary font-bold' : 'text-foreground' }}">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <span class="text-[10px] font-black">{{ $company->code ?: substr($company->name, 0, 2) }}</span>
                                </div>
                                <span class="truncate">{{ $company->name }}</span>
                            </button>
                        @endforeach
                    </form>
                    
                    <div class="mt-2 border-t border-border pt-2">
                        <a href="{{ route('companies.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-muted-foreground transition hover:bg-secondary hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                            </svg>
                            Manage Companies
                        </a>
                    </div>
                </div>
            </div>

            <x-layout.notifications-dropdown />

            <x-layout.inbox-dropdown />

            <x-layout.settings-dropdown />

            <div class="hidden h-8 w-px bg-border/40 md:block"></div>

            <x-layout.user-dropdown />
        </div>
    </div>
</header>
