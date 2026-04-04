@props([
    'title',
    'icon',
    'items' => [],
])

<div x-data="{ open: true }" class="space-y-1">
    <button
        type="button"
        @click="open = !open; if (sidebarCollapsed) { open = false; }"
        class="flex w-full items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-semibold text-sidebar-foreground transition-all duration-300 hover:bg-primary/5 hover:text-primary"
        :class="sidebarCollapsed ? 'justify-center px-2' : ''"
    >
        <span class="shrink-0">{!! $icon !!}</span>
        <span x-show="!sidebarCollapsed" class="truncate">{{ $title }}</span>
        <svg x-show="!sidebarCollapsed" class="ml-auto size-4 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open && !sidebarCollapsed"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="space-y-1 pl-3"
    >
        @foreach($items as $item)
            <x-layout.nav-link
                :title="$item['title']"
                :url="$item['url']"
                :active="request()->is($item['pattern'] ?? '__never__')"
                :icon="$item['icon'] ?? ''"
                :badge="$item['badge'] ?? null"
            />
        @endforeach
    </div>
</div>
