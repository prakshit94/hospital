@props(['pageTitle' => null, 'hideSidebarToggle' => false])

@php
    $title = $pageTitle ?: config('enterprise-ui.app_name', config('app.name'));
@endphp

<header class="sticky top-0 z-40 m-4 flex min-h-20 items-center justify-between rounded-[1.9rem] border border-white/40 bg-white/55 px-5 shadow-[0_24px_60px_-34px_rgba(15,23,42,0.32)] backdrop-blur-3xl dark:border-white/8 dark:bg-white/6 md:m-6 md:px-6">
    <div class="flex items-center gap-5">
        @unless($hideSidebarToggle)
            <button
                type="button"
                @click="toggleSidebar()"
                class="rounded-2xl border border-white/35 bg-white/45 p-2.5 text-muted-foreground transition duration-300 hover:-translate-y-0.5 hover:bg-white/75 hover:text-foreground dark:border-white/8 dark:bg-white/6 dark:hover:bg-white/10"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 6h16M4 12h16M14 18h6" />
                </svg>
            </button>
            <div class="h-8 w-px bg-gradient-to-b from-transparent via-border/60 to-transparent"></div>
        @endunless

        <div class="hidden items-center gap-2 md:flex">
            <span class="rounded-full border border-white/35 bg-white/55 px-3 py-2 text-[11px] font-black uppercase tracking-[0.24em] text-muted-foreground shadow-[inset_0_1px_0_rgba(255,255,255,0.85)] dark:border-white/8 dark:bg-white/7 dark:shadow-none">
                {{ config('enterprise-ui.workspace_name', 'Workspace') }}
            </span>
            <svg class="size-4 text-muted-foreground/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
            </svg>
            <span class="rounded-full border border-white/20 bg-[linear-gradient(135deg,color-mix(in_oklab,var(--primary)_88%,white_12%),color-mix(in_oklab,var(--primary)_70%,rgb(56_189_248)_30%))] px-4 py-2 text-[11px] font-black uppercase tracking-[0.24em] text-primary-foreground shadow-[0_20px_45px_-22px_color-mix(in_oklab,var(--primary)_60%,transparent)]">
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
