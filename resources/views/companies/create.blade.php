@extends('layouts.app')

@php
    $pageTitle = 'New Company';
@endphp

@section('content')
<div class="page-stack">

    {{-- ═══════════════════════ HERO PANEL ═══════════════════════ --}}
    <section class="hero-panel">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="hero-kicker">Business Directory</span>
                <h1 class="hero-title">Add New Company</h1>
                <p class="hero-copy">Register a new corporate client for health records management.</p>
            </div>
            <x-ui.button variant="ghost" href="{{ route('companies.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
                Back to Companies
            </x-ui.button>
        </div>
    </section>

    {{-- ═══════════════════════ FORM CARD ═══════════════════════ --}}
    <div class="mx-auto max-w-3xl">
        <x-ui.card>
            <div class="mb-6 border-b border-border/70 pb-6">
                <div class="section-kicker">Company Details</div>
                <h2 class="section-title">Fill in the company information</h2>
                <p class="section-copy">All fields marked with <span class="text-destructive font-bold">*</span> are required.</p>
            </div>

            <form action="{{ route('companies.store') }}" method="POST" class="space-y-6">
                @csrf

                @include('companies._form', ['company' => new \App\Models\Company()])

                <div class="flex items-center justify-end gap-3 border-t border-border/70 pt-6">
                    <x-ui.button variant="ghost" href="{{ route('companies.index') }}">Cancel</x-ui.button>
                    <x-ui.button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        Save Company
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>

</div>
@endsection
