@php
    $modalMode = true;
@endphp

<div class="p-5 sm:p-6 lg:p-7">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h2 class="font-heading text-2xl font-black tracking-tight text-foreground">
                {{ $pageTitle }}
            </h2>
            <p class="mt-2 text-sm text-muted-foreground">
                {{ $pageDescription }}
            </p>
        </div>

        <button
            type="button"
            data-modal-close
            class="flex h-11 w-11 items-center justify-center rounded-[1rem] border border-border bg-secondary text-muted-foreground hover:text-foreground"
        >
            ✕
        </button>
    </div>

    <form method="POST" action="{{ $formAction }}" data-modal-form class="space-y-6">
        @csrf
        @if($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <div data-modal-error-summary class="modal-error-summary hidden"></div>

        {{-- ✅ FULL FORM CONTENT HERE --}}
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.25fr)_minmax(320px,0.75fr)]">

            {{-- LEFT --}}
            <x-ui.card class="space-y-6">
                <div>
                    <div class="section-kicker">Permission Details</div>
                    <h2 class="section-title">Define a reusable ability</h2>
                    <p class="section-copy">
                        Create a granular system capability that can be attached to roles.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="ui-field">
                        <label class="ui-label">Permission Name</label>
                        <input name="name" value="{{ old('name', $permission->name) }}" class="ui-input" required>
                    </div>

                    <div class="ui-field">
                        <label class="ui-label">Slug</label>
                        <input name="slug" value="{{ old('slug', $permission->slug) }}" class="ui-input">
                    </div>

                    <div class="ui-field md:col-span-2">
                        <label class="ui-label">Group</label>
                        <input name="group_name" value="{{ old('group_name', $permission->group_name) }}" class="ui-input" required>
                    </div>

                    <div class="ui-field md:col-span-2">
                        <label class="ui-label">Description</label>
                        <textarea name="description" class="ui-textarea">
{{ old('description', $permission->description) }}
                        </textarea>
                    </div>
                </div>
            </x-ui.card>

            {{-- RIGHT --}}
            <div class="space-y-6">
                <x-ui.card class="space-y-4">
                    <div>
                        <div class="section-kicker">Design Note</div>
                        <h2 class="section-title">Keep permission names clear</h2>
                        <p class="section-copy">
                            Use consistent naming for clarity in logs and UI.
                        </p>
                    </div>
                </x-ui.card>

                <x-ui.card class="space-y-3">
                    <x-ui.button type="submit" class="w-full">
                        {{ $submitLabel }}
                    </x-ui.button>

                    @if($modalMode)
                        <button type="button" data-modal-close class="w-full border rounded-lg py-2">
                            Cancel
                        </button>
                    @else
                        <x-ui.button variant="secondary" href="{{ route('permissions.index') }}" class="w-full">
                            Cancel
                        </x-ui.button>
                    @endif
                </x-ui.card>
            </div>

        </div>
    </form>
</div>