@props([
    'title',
    'url' => '#',
    'active' => false,
    'icon' => null,
    'badge' => null,
    'showTooltip' => true,
])

<a
    href="{{ $url }}"
    {{ $attributes->merge(['class' => 'group relative flex items-center rounded-2.5xl p-3 text-sm font-medium transition-all duration-300']) }}
    @class([
        'gap-3' => $icon,
        'border' => !$attributes->has('class') || !str_contains($attributes->get('class'), 'border-none'),
        'border-border bg-secondary text-primary shadow-[0_10px_24px_-20px_color-mix(in_oklab,var(--primary)_40%,transparent)]' => $active,
        'border-transparent text-sidebar-foreground hover:border-border hover:bg-secondary hover:text-primary' => !$active,
    ])
    @click="if (window.innerWidth < 768) { closeMobileMenu() }"
    :class="sidebarCollapsed ? 'justify-center px-2' : ''"
>
    @if($active)
        <span class="absolute left-[-13px] top-1/2 h-8 w-1.5 -translate-y-1/2 rounded-r-full bg-primary shadow-[0_0_20px_color-mix(in_oklab,var(--primary)_80%,transparent)]"></span>
    @endif

    @if($icon)
        <span
            class="shrink-0 flex items-center justify-center rounded-xl p-2.5 transition-all duration-500 group-hover:scale-125"
            @class([
                'bg-primary/12 text-primary' => $active,
                'text-primary/70 bg-primary/8 group-hover:bg-primary/12' => !$active && !$showTooltip,
                'text-sidebar-foreground/50 group-hover:bg-secondary group-hover:text-primary' => !$active && $showTooltip,
            ])
        >
            {!! $icon !!}
        </span>
    @endif

    <span
        x-show="!sidebarCollapsed || !{{ $showTooltip ? 'true' : 'false' }}"
        class="truncate text-[12px] font-semibold transition-colors duration-300"
    >
        {{ $title }}
    </span>

    {{-- Floating Label (Collapsed Sidebar) --}}
    @if($showTooltip)
        <div
            x-show="sidebarCollapsed"
            class="pointer-events-none absolute left-full top-1/2 z-[110] ml-6 -translate-y-1/2 scale-90 opacity-0 transition-all duration-300 group-hover:pointer-events-auto group-hover:scale-100 group-hover:opacity-100"
        >
            <div class="whitespace-nowrap rounded-[1rem] border border-sidebar-border bg-sidebar px-4 py-2.5 text-[10px] font-semibold text-foreground shadow-[0_20px_40px_-20px_rgba(0,0,0,0.25)]">
                {{ $title }}
            </div>
        </div>
    @endif

    @if($badge)
        <span
            x-show="!sidebarCollapsed || !{{ $showTooltip ? 'true' : 'false' }}"
            class="ml-auto flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1.5 text-[9px] font-black text-primary-foreground shadow-[0_10px_20px_-8px_color-mix(in_oklab,var(--primary)_70%,transparent)]"
        >
            {{ $badge }}
        </span>
    @endif
</a>
