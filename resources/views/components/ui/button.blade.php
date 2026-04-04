@props([
    'variant' => 'primary',
    'href' => null,
])

@php
    $classes = match ($variant) {
        'secondary' => 'border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent',
        'ghost' => 'border border-transparent bg-transparent text-foreground hover:border-border hover:bg-secondary',
        default => 'border border-primary bg-primary text-primary-foreground shadow-[0_18px_34px_-22px_color-mix(in_oklab,var(--primary)_45%,transparent)] hover:brightness-105',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2.5 text-sm font-semibold transition duration-300 active:scale-[0.98] ' . $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2.5 text-sm font-semibold transition duration-300 active:scale-[0.98] ' . $classes]) }}>
        {{ $slot }}
    </button>
@endif
