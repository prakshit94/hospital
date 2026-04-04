@props([
    'active' => false,
    'onLabel' => 'Active',
    'offLabel' => 'Inactive',
    'action' => '#',
    'method' => 'POST'
])

<form action="{{ $action }}" method="POST" data-async-form>
    @csrf
    @method($method)
    <button
        type="submit"
        class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-300 focus:outline-none {{ $active ? 'bg-primary' : 'bg-muted' }}"
        role="switch"
        aria-checked="{{ $active ? 'true' : 'false' }}"
    >
        <span
            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 {{ $active ? 'translate-x-6' : 'translate-x-1' }}"
        ></span>
    </button>
</form>
