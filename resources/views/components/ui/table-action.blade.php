@props([
    'href' => null,
    'label',
    'tone' => 'neutral',
])

@php
    $classes = match ($tone) {
        'primary' => 'border-primary/20 bg-primary/10 text-primary hover:border-primary/35 hover:bg-primary/15',
        default => 'border-border bg-secondary/70 text-foreground hover:border-primary/20 hover:bg-secondary',
    };
@endphp

@if($href)
    <a
        href="{{ $href }}"
        aria-label="{{ $label }}"
        title="{{ $label }}"
        {{ $attributes->merge(['class' => 'table-action-link ' . $classes]) }}
    >
        <span class="table-action-icon" aria-hidden="true">{{ $slot }}</span>
        <span>{{ $label }}</span>
    </a>
@else
    <button
        type="button"
        aria-label="{{ $label }}"
        title="{{ $label }}"
        {{ $attributes->merge(['class' => 'table-action-link ' . $classes]) }}
    >
        <span class="table-action-icon" aria-hidden="true">{{ $slot }}</span>
        <span>{{ $label }}</span>
    </button>
@endif
