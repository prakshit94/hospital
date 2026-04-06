@extends('layouts.app')

@php
    $pageTitle = 'Edit Customer: ' . $customer->display_name;
    $submitLabel = 'Update Record';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-3">
                <a href="{{ route('customers.show', $customer->uuid) }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-muted-foreground transition hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                    Back to Profile
                </a>
                <h1 class="hero-title">Refine Profile</h1>
                <p class="hero-copy">Update contact details, business entity information, and financial limits for <span class="text-foreground font-black italic">{{ $customer->display_name }}</span>.</p>
            </div>
        </section>

        <form method="POST" action="{{ route('customers.update', $customer->uuid) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('customers._form')
        </form>
    </div>
@endsection
