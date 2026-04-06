@extends('layouts.app')

@php
    $pageTitle = 'Create Customer';
    $submitLabel = 'Register Profile';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-3">
                <a href="{{ route('customers.index') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-muted-foreground transition hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                    Back to Registry
                </a>
                <h1 class="hero-title">New Customer Profile</h1>
                <p class="hero-copy">Onboard a new farmer or business entity into the ecosystem with full KYC and territory linkage.</p>
            </div>
        </section>

        <form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data">
            @csrf
            @include('customers._form')
        </form>
    </div>
@endsection
