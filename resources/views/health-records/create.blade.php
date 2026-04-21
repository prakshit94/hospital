@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading text-3xl font-bold tracking-tight text-foreground">New Health Record</h1>
                <p class="text-muted-foreground">Register a new employee health assessment.</p>
            </div>
            <a href="{{ route('health-records.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-border bg-background px-4 text-sm font-medium text-foreground transition hover:bg-secondary">
                Cancel
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('health-records.store') }}" method="POST" class="rounded-[2.5rem] border border-border bg-card p-10 shadow-sm">
            @csrf

            @if ($errors->any())
                <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                        Validation Errors
                    </div>
                    <ul class="list-inside list-disc text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @include('health-records._form')

            <div class="mt-10 flex items-center justify-end gap-3 border-t border-border pt-10">
                <button type="button" 
                        onclick="const form = this.closest('form'); form.querySelectorAll('input:not([name=\'_token\']), textarea').forEach(i => i.value = ''); form.querySelectorAll('select').forEach(s => s.selectedIndex = 0); if(window.calculateBMI) window.calculateBMI();"
                        class="inline-flex h-12 items-center justify-center rounded-2xl border border-border bg-card px-8 text-sm font-bold text-muted-foreground transition hover:bg-secondary hover:text-foreground">
                    Clear All
                </button>
                <button type="submit" class="inline-flex h-12 items-center justify-center rounded-2xl bg-primary px-10 text-sm font-bold text-white shadow-[0_15px_30px_-10px_color-mix(in_oklab,var(--primary)_50%,transparent)] transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Save Health Record
                </button>
            </div>
        </form>
    </div>
@endsection
