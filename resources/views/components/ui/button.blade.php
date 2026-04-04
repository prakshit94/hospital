@props([
    'variant' => 'primary',
    'href' => null,
])

@php
    $classes = match ($variant) {
        'secondary' => 'border border-white/45 bg-white/55 text-foreground shadow-[0_18px_40px_-24px_rgba(15,23,42,0.28)] backdrop-blur-xl hover:bg-white/70 dark:border-white/10 dark:bg-white/8 dark:hover:bg-white/12',
        'ghost' => 'border border-transparent bg-transparent text-foreground hover:border-white/35 hover:bg-white/45 hover:shadow-[0_18px_40px_-24px_rgba(15,23,42,0.18)] dark:hover:border-white/8 dark:hover:bg-white/8',
        default => 'border border-white/30 bg-[linear-gradient(135deg,color-mix(in_oklab,var(--primary)_90%,white_10%)_0%,color-mix(in_oklab,var(--primary)_70%,rgb(56_189_248)_30%)_100%)] text-primary-foreground shadow-[0_24px_50px_-24px_color-mix(in_oklab,var(--primary)_55%,transparent)] hover:brightness-105 dark:border-white/10',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition duration-300 active:scale-[0.98] ' . $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition duration-300 active:scale-[0.98] ' . $classes]) }}>
        {{ $slot }}
    </button>
@endif
