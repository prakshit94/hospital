@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'premium-panel relative overflow-hidden p-6 before:pointer-events-none before:absolute before:inset-x-5 before:top-0 before:h-px before:bg-[linear-gradient(90deg,transparent,rgba(255,255,255,0.9),transparent)] dark:before:bg-[linear-gradient(90deg,transparent,rgba(255,255,255,0.18),transparent)] ' . $class]) }}>
    {{ $slot }}
</div>
