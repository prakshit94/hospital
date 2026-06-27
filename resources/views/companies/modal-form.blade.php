@php
    $modalMode = true;
@endphp

<div class="p-5 sm:p-6 lg:p-7">
    {{-- Modal Header --}}
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h2 class="font-heading text-2xl font-black tracking-tight text-foreground">{{ $pageTitle }}</h2>
            <p class="mt-2 text-sm text-muted-foreground">{{ $pageDescription }}</p>
        </div>
        <button
            type="button"
            data-modal-close
            class="flex h-11 w-11 items-center justify-center rounded-[1rem] border border-border bg-secondary text-muted-foreground transition duration-300 hover:text-foreground"
            aria-label="Close modal"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Modal Form --}}
    <form method="POST" action="{{ $formAction }}" data-modal-form class="space-y-6">
        @csrf
        @if($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <div data-modal-error-summary class="modal-error-summary hidden"></div>

        @include('companies._form')

        {{-- Form Footer --}}
        <div class="flex items-center justify-end gap-3 border-t border-border/70 pt-5">
            <x-ui.button variant="ghost" type="button" data-modal-close>Cancel</x-ui.button>
            <x-ui.button type="submit">
                {{ $submitLabel }}
            </x-ui.button>
        </div>
    </form>
</div>
