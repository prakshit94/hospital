@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-border/60 bg-card/70 p-6 shadow-sm backdrop-blur-xl ' . $class]) }}>
    {{ $slot }}
</div>

