@props([
    'title',
    'url' => '#',
    'active' => false,
    'icon' => null,
    'badge' => null,
])

<a
    href="{{ $url }}"
    class="group relative flex items-center gap-3 rounded-2xl border p-3 text-sm font-medium transition-all duration-300"
    :class="sidebarCollapsed ? 'justify-center px-2' : ''"
    @class([
        'border-primary/15 bg-primary/10 text-primary shadow-sm' => $active,
        'border-transparent text-sidebar-foreground hover:border-border/60 hover:bg-primary/5 hover:text-primary' => !$active,
    ])
>
    @if($active)
        <span class="absolute left-[-13px] top-1/2 h-6 w-1.5 -translate-y-1/2 rounded-r-full bg-primary"></span>
    @endif

    <span class="shrink-0">{!! $icon !!}</span>
    <span x-show="!sidebarCollapsed" class="truncate">{{ $title }}</span>

    @if($badge)
        <span x-show="!sidebarCollapsed" class="ml-auto rounded-md bg-primary px-1.5 py-0.5 text-[10px] font-bold text-primary-foreground">{{ $badge }}</span>
    @endif
</a>

