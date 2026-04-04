@props([
    'title',
    'url' => '#',
    'active' => false,
    'icon' => null,
    'badge' => null,
])

<a
    href="{{ $url }}"
    class="group relative flex items-center gap-3 rounded-[1.35rem] border p-3 text-sm font-medium transition-all duration-300"
    :class="sidebarCollapsed ? 'justify-center px-2' : ''"
    @class([
        'border-white/35 bg-[linear-gradient(135deg,color-mix(in_oklab,var(--primary)_15%,white_85%),rgba(255,255,255,0.56))] text-primary shadow-[0_18px_42px_-26px_color-mix(in_oklab,var(--primary)_45%,transparent)] dark:border-white/10 dark:bg-[linear-gradient(135deg,color-mix(in_oklab,var(--primary)_18%,transparent),rgba(255,255,255,0.05))]' => $active,
        'border-transparent text-sidebar-foreground hover:border-white/30 hover:bg-white/45 hover:text-primary dark:hover:border-white/8 dark:hover:bg-white/6' => !$active,
    ])
>
    @if($active)
        <span class="absolute left-[-13px] top-1/2 h-7 w-1.5 -translate-y-1/2 rounded-r-full bg-primary shadow-[0_0_16px_color-mix(in_oklab,var(--primary)_60%,transparent)]"></span>
    @endif

    <span class="shrink-0 rounded-[1rem] p-2 transition duration-300 group-hover:bg-white/40 dark:group-hover:bg-white/8">{!! $icon !!}</span>
    <span x-show="!sidebarCollapsed" class="truncate font-semibold">{{ $title }}</span>

    @if($badge)
        <span x-show="!sidebarCollapsed" class="ml-auto rounded-full bg-primary px-2 py-1 text-[10px] font-bold text-primary-foreground shadow-[0_16px_32px_-20px_color-mix(in_oklab,var(--primary)_70%,transparent)]">{{ $badge }}</span>
    @endif
</a>
