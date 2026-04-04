@props([
    'title',
    'icon',
    'items' => [],
    'active' => false,
])

<div
    x-data="{
        open: @js($active),
        hovering: false,
        dropUp: false
    }"
    class="relative space-y-1"
    @mouseenter="hovering = true; dropUp = $el.getBoundingClientRect().top > (window.innerHeight / 2)"
    @mouseleave="hovering = false"
>
    <button
        type="button"
        @click="if (!sidebarCollapsed) { open = !open }"
        class="group flex w-full items-center gap-3 rounded-2.5xl px-3 py-3 text-sm font-semibold transition-all duration-300"
        :class="[
            (open && !sidebarCollapsed) ? 'bg-secondary text-primary' : 'text-sidebar-foreground hover:bg-secondary hover:text-primary',
            sidebarCollapsed ? 'justify-center px-2' : ''
        ]"
    >
        <div class="flex shrink-0 transition-transform duration-300 group-hover:scale-110" :class="(open && !sidebarCollapsed) ? 'text-primary' : 'text-sidebar-foreground/40 group-hover:text-primary'">
            {!! $icon !!}
        </div>
        <span x-show="!sidebarCollapsed" class="truncate text-[12px]">{{ $title }}</span>
        <svg
            x-show="!sidebarCollapsed"
            class="ml-auto size-4 transition-transform duration-500"
            :class="open ? 'rotate-180 text-primary' : 'text-sidebar-foreground/20'"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Tree sub-menu (Expanded Sidebar) --}}
    <div
        x-show="open && !sidebarCollapsed"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="ml-[1.6rem] space-y-0.5 border-l-2 border-primary/15 pl-4 transition-all duration-500"
    >
        @foreach($items as $item)
            <x-layout.nav-link
                :title="$item['title']"
                :url="$item['url']"
                :active="request()->is($item['pattern'] ?? '__never__')"
                :icon="$item['icon_svg'] ?? null"
                :badge="$item['badge'] ?? null"
                :showTooltip="false"
                class="!p-2.5 border-none bg-transparent hover:!bg-secondary"
            />
        @endforeach
    </div>

    {{-- Floating Popover (Collapsed Sidebar) --}}
    <div
        x-show="sidebarCollapsed && hovering"
        x-transition:enter="transition-all ease-out duration-400"
        x-transition:enter-start="opacity-0 translate-x-12 blur-md scale-95"
        x-transition:enter-end="opacity-100 translate-x-0 blur-0 scale-100"
        x-transition:leave="transition-all ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0 blur-0 scale-100"
        x-transition:leave-end="opacity-0 translate-x-10 blur-md scale-95"
        class="pointer-events-none absolute left-full z-[120] pl-6"
        :class="dropUp ? 'bottom-0' : 'top-0'"
    >
        <div class="pointer-events-auto min-w-[240px] space-y-1 rounded-[1.4rem] border border-sidebar-border bg-sidebar p-4 shadow-[0_24px_50px_-28px_rgba(0,0,0,0.28)]">
            <div class="mb-4 border-b border-sidebar-border px-3 py-2 text-sm font-semibold text-foreground">
                <span>{{ $title }}</span>
            </div>
            <div class="space-y-1">
                @foreach($items as $item)
                    <x-layout.nav-link
                        :title="$item['title']"
                        :url="$item['url']"
                        :active="request()->is($item['pattern'] ?? '__never__')"
                        :icon="$item['icon_svg'] ?? null"
                        :badge="$item['badge'] ?? null"
                        :showTooltip="false"
                        class="!p-2.5 border-none bg-transparent hover:!bg-secondary"
                    />
                @endforeach
            </div>
        </div>
    </div>
</div>
