@extends('layouts.app')

@php
    $pageTitle = 'Edit — ' . $company->name;
@endphp

@section('content')
<div class="page-stack">

    {{-- ═══════════════════════ HERO PANEL ═══════════════════════ --}}
    <section class="hero-panel">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="hero-kicker">Business Directory</span>
                <h1 class="hero-title">Edit Company</h1>
                <p class="hero-copy">Updating details for <strong class="text-foreground">{{ $company->name }}</strong>.</p>
            </div>
            <x-ui.button variant="ghost" href="{{ route('companies.show', $company) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
                Back
            </x-ui.button>
        </div>
    </section>

    {{-- ═══════════════════════ FORM CARD ═══════════════════════ --}}
    <div class="mx-auto max-w-3xl">
        <x-ui.card>
            <div class="mb-6 border-b border-border/70 pb-6">
                <div class="section-kicker">Company Details</div>
                <h2 class="section-title">Update company information</h2>
                <p class="section-copy">Edit the fields below and save to apply your changes.</p>
            </div>

            <form action="{{ route('companies.update', $company->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @include('companies._form')

                <div class="flex items-center justify-between gap-3 border-t border-border/70 pt-6">
                    <button
                        type="button"
                        onclick="if(confirm('Are you sure you want to delete this company? This may affect linked health records.')) document.getElementById('delete-company-form').submit();"
                        class="text-sm font-bold text-destructive transition duration-300 hover:text-destructive/70"
                    >
                        Delete Company
                    </button>
                    <div class="flex items-center gap-3">
                        <x-ui.button variant="ghost" href="{{ route('companies.show', $company) }}">Cancel</x-ui.button>
                        <x-ui.button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Update Company
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </x-ui.card>
    </div>

</div>

{{-- Hidden delete form --}}
<form id="delete-company-form" action="{{ route('companies.destroy', $company->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection
