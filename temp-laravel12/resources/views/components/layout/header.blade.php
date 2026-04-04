@props(['pageTitle' => null, 'hideSidebarToggle' => false])

@php
    $title = $pageTitle ?: config('enterprise-ui.app_name', config('app.name'));
@endphp

<header class="sticky top-0 z-40 flex h-20 items-center justify-between border-b border-border/60 bg-white/70 px-6 backdrop-blur-3xl shadow-[0_4px_30px_rgba(0,0,0,0.03)] dark:bg-zinc-950/80">
    <div class="flex items-center gap-5">
        @unless($hideSidebarToggle)
            <button
                type="button"
                @click="toggleSidebar()"
                class="rounded-2xl border border-transparent p-2.5 text-muted-foreground transition hover:border-border/60 hover:bg-secondary/50 hover:text-foreground"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 6h16M4 12h16M14 18h6" />
                </svg>
            </button>
            <div class="h-8 w-px bg-gradient-to-b from-transparent via-border/60 to-transparent"></div>
        @endunless

        <div class="hidden items-center gap-2 md:flex">
            <span class="rounded-xl bg-secondary/50 px-3 py-2 text-xs font-black uppercase tracking-[0.2em] text-muted-foreground">
                {{ config('enterprise-ui.workspace_name', 'Workspace') }}
            </span>
            <svg class="size-4 text-muted-foreground/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
            </svg>
            <span class="rounded-xl bg-primary px-3 py-2 text-xs font-black uppercase tracking-[0.2em] text-primary-foreground shadow-lg shadow-primary/20">
                {{ $title }}
            </span>
        </div>
    </div>

    <div class="flex items-center gap-3 md:gap-5">
        <x-layout.theme-toggle />

        <div class="hidden items-center gap-1 rounded-2xl border border-border/60 bg-secondary/30 p-1 md:flex">
            @foreach(['blue', 'green', 'orange', 'rose'] as $themeName)
                @php
                    $swatches = [
                        'blue' => 'bg-sky-500',
                        'green' => 'bg-emerald-500',
                        'orange' => 'bg-orange-500',
                        'rose' => 'bg-rose-500',
                    ];
                @endphp
                <button
                    type="button"
                    @click="setColorTheme('{{ $themeName }}')"
                    class="size-8 rounded-xl border border-transparent transition hover:scale-105"
                    :class="colorTheme === '{{ $themeName }}' ? 'ring-2 ring-primary/30' : ''"
                >
                    <span class="block size-full rounded-[10px] {{ $swatches[$themeName] }}"></span>
                </button>
            @endforeach
        </div>

        <x-layout.user-dropdown />
    </div>
</header>

