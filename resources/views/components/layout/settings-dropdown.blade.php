@php
    $themes = [
        ['value' => 'light', 'label' => 'Day'],
        ['value' => 'dark', 'label' => 'Night'],
        ['value' => 'system', 'label' => 'Auto'],
    ];

    $swatches = [
        'blue' => 'from-sky-400 to-indigo-500',
        'green' => 'from-emerald-400 to-teal-500',
        'orange' => 'from-orange-400 to-amber-600',
        'rose' => 'from-rose-400 to-pink-600',
    ];
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        @click="open = !open"
        class="flex h-11 w-11 items-center justify-center rounded-[1rem] border border-border bg-card text-muted-foreground shadow-[0_14px_32px_-26px_rgba(15,23,42,0.18)] transition duration-300 hover:bg-secondary hover:text-primary"
        aria-label="Open display settings"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 3v2.5"/>
            <path d="M12 18.5V21"/>
            <path d="m4.93 4.93 1.77 1.77"/>
            <path d="m17.3 17.3 1.77 1.77"/>
            <path d="M3 12h2.5"/>
            <path d="M18.5 12H21"/>
            <path d="m4.93 19.07 1.77-1.77"/>
            <path d="m17.3 6.7 1.77-1.77"/>
            <circle cx="12" cy="12" r="3.25"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition
        class="absolute right-0 z-50 mt-3 w-[18rem] overflow-hidden rounded-[1.4rem] border border-border bg-popover p-3 shadow-[0_24px_50px_-30px_rgba(15,23,42,0.3)]"
    >
        <div class="mb-3">
            <div class="text-sm font-semibold text-foreground">Display Settings</div>
            <div class="mt-1 text-xs text-muted-foreground">Theme mode and accent color.</div>
        </div>

        <div class="space-y-4">
            <div>
                <div class="mb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-muted-foreground">Mode</div>
                <div class="grid grid-cols-3 gap-2 rounded-[1rem] border border-border bg-secondary p-1">
                    @foreach($themes as $item)
                        <button
                            type="button"
                            @click="setTheme('{{ $item['value'] }}')"
                            class="rounded-[0.8rem] px-3 py-2 text-[11px] font-semibold transition"
                            :class="theme === '{{ $item['value'] }}' ? 'bg-card text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                        >
                            {{ $item['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="mb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-muted-foreground">Accent</div>
                <div class="flex items-center gap-2">
                    @foreach($swatches as $themeName => $gradient)
                        <button
                            type="button"
                            @click="setColorTheme('{{ $themeName }}')"
                            class="relative size-8 rounded-full transition-transform hover:scale-105"
                            :class="colorTheme === '{{ $themeName }}' ? 'ring-2 ring-primary/40 ring-offset-2 ring-offset-card' : ''"
                            aria-label="Set {{ $themeName }} accent"
                        >
                            <span class="block size-full rounded-full bg-gradient-to-br {{ $gradient }}"></span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
