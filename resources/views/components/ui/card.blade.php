@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'premium-panel relative overflow-hidden p-5 sm:p-6 ' . $class]) }}>
    {{ $slot }}
</div>
