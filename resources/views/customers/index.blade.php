@extends('layouts.app')

@php
    $pageTitle = 'Customers';
    $user = auth()->user();
@endphp

@section('content')
    <div class="page-stack">

        <!-- 🔷 HERO -->
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                <div>
                    <span class="hero-kicker">CRM</span>
                    <h1 class="hero-title">Customers</h1>
                    <p class="hero-copy">
                        Manage farmer database, credit limits, and business territories.
                    </p>
                </div>

                {{-- ✅ FIXED: Use custom permission check --}}
                @if($user?->hasPermission('customers.create'))
                    <x-ui.button 
                        href="{{ route('customers.create') }}" 
                        data-modal-open="customer-create-modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.2">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        New Customer
                    </x-ui.button>
                @endif

            </div>
        </section>

        <!-- 🔷 DATA SECTION -->
        <section class="data-shell">

            <!-- Header -->
            <div class="section-header">
                <div>
                    <div class="section-kicker">Filters</div>
                    <h2 class="section-title">Browse and refine customer records</h2>
                    <p class="section-copy">
                        Search by name, status, type, or lead stage.
                    </p>
                </div>
            </div>

            <!-- Filters -->
            <form 
                method="GET" 
                action="{{ route('customers.index') }}" 
                data-async-search 
                data-target="#customers-results"
                class="data-toolbar lg:grid-cols-[minmax(0,1.3fr)_repeat(4,minmax(0,0.7fr))]"
            >

                <!-- 🔍 Search -->
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search name, mobile, code..."
                    class="ui-input"
                >

                <!-- Status -->
                <select name="status" class="ui-select">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    <option value="deleted" @selected(request('status') === 'deleted')>
                        Deleted (Archived)
                    </option>
                </select>

                <!-- Type -->
                <select name="type" class="ui-select">
                    <option value="">All types</option>
                    @foreach([
                        'farmer' => 'Farmer',
                        'buyer' => 'Buyer',
                        'vendor' => 'Vendor',
                        'dealer' => 'Dealer'
                    ] as $value => $label)
                        <option value="{{ $value }}" @selected(request('type') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <!-- Lead Status -->
                <select name="lead_status" class="ui-select">
                    <option value="">Lead status</option>
                    @foreach([
                        'lead' => 'Lead',
                        'converted' => 'Converted',
                        'inactive' => 'Inactive'
                    ] as $value => $label)
                        <option value="{{ $value }}" @selected(request('lead_status') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <!-- Per Page -->
                <select name="per_page" class="ui-select">
                    @foreach([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>
                            {{ $size }} per page
                        </option>
                    @endforeach
                </select>

            </form>

            <!-- Results -->
            <div id="customers-results">
                @include('customers.partials.results')
            </div>

        </section>

    </div>
@endsection