@php
    $modalMode = true;

    // ✅ Safe defaults (prevents undefined variable errors)
    $pageTitle = $pageTitle ?? 'Create Customer';
    $pageDescription = $pageDescription ?? 'Fill in customer details below.';
    $formAction = $formAction ?? route('customers.store');
    $formMethod = $formMethod ?? 'POST';
@endphp

<div class="p-5 sm:p-6 lg:p-7">

    <!-- 🔷 HEADER -->
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h2 class="font-heading text-2xl font-black tracking-tight text-foreground">
                {{ $pageTitle }}
            </h2>
            <p class="mt-2 text-sm text-muted-foreground">
                {{ $pageDescription }}
            </p>
        </div>

        <!-- ❌ CLOSE BUTTON -->
        <button
            type="button"
            data-modal-close
            class="flex h-11 w-11 items-center justify-center rounded-[1rem] border border-border bg-secondary text-muted-foreground transition hover:text-foreground"
            aria-label="Close modal"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- 🔷 FORM -->
    <form 
        method="POST"
        action="{{ $formAction }}"
        enctype="multipart/form-data"
        data-modal-form
        class="space-y-6"
    >
        @csrf

        @if($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <!-- 🔴 ERROR SUMMARY -->
        <div 
            data-modal-error-summary 
            class="modal-error-summary hidden"
        ></div>

        <!-- 🔷 FORM FIELDS -->
        @include('customers._form', ['modalMode' => true])

    </form>
</div>