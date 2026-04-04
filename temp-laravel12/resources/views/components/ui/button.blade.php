@props([
    'variant' => 'primary',
    'href' => null,
])

@php
    $classes = match ($variant) {
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'ghost' => 'bg-transparent text-foreground hover:bg-secondary/50',
        default => 'bg-primary text-primary-foreground hover:bg-primary/90 shadow-lg shadow-primary/20',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-2xl px-4 py-2.5 text-sm font-semibold transition active:scale-[0.98] ' . $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-2xl px-4 py-2.5 text-sm font-semibold transition active:scale-[0.98] ' . $classes]) }}>
        {{ $slot }}
    </button>
@endif

