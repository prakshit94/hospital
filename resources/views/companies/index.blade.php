@extends('layouts.app')

@php
    $pageTitle = 'Companies';
@endphp

@section('content')
<div data-company-data="{{ $companies->mapWithKeys(fn($c) => [$c->id => $c->trashed()])->toJson() }}" x-data="{
    selected: [],
    selectAll: false,
    companyData: {},
    init() {
        this.companyData = JSON.parse(this.$el.dataset.companyData || '{}');
    },
    allIds() {
        return Object.keys(this.companyData);
    },
    toggleAll() {
        this.selected = this.selectAll ? this.allIds() : [];
    },
    isAnyDeleted() {
        return this.selected.some(id => this.companyData[id]);
    },
    bulkAction(action) {
        if (this.selected.length === 0) return;
        
        let confirmMsg = 'Are you sure?';
        if (action === 'delete') confirmMsg = 'Move selected companies to trash?';
        if (action === 'force-delete') confirmMsg = 'CRITICAL: PERMANENTLY delete selected records?';
        if (action === 'restore') confirmMsg = 'Restore selected companies?';
        
        if (['delete', 'force-delete', 'restore'].includes(action) && !confirm(confirmMsg)) return;
        
        fetch('{{ route('companies.bulk-action') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ action: action, ids: this.selected })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'success', title: 'Success', message: data.message }}));
                window.location.reload();
            } else {
                window.dispatchEvent(new CustomEvent('toast-notify', { detail: { type: 'error', title: 'Error', message: data.message }}));
            }
            this.selected = [];
            this.selectAll = false;
        });
    }
}" class="page-stack">
    <section class="hero-panel">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="hero-kicker">Business Directory</span>
                <h1 class="hero-title">Companies</h1>
                <p class="hero-copy">Manage corporate clients and their health record access.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1 p-1 rounded-2xl border border-border bg-secondary/50">
                    <a href="{{ route('companies.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 {{ !request('status') ? 'bg-card text-primary shadow-sm border border-border' : 'text-muted-foreground hover:text-foreground' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 21h18"/><path d="M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4"/><path d="M5 21V10.85"/><path d="M19 21V10.85"/><path d="M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"/></svg>
                        Active
                    </a>
                    <a href="{{ route('companies.index', ['status' => 'deleted']) }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 {{ request('status') === 'deleted' ? 'bg-destructive/10 text-destructive shadow-sm border border-destructive/20' : 'text-muted-foreground hover:text-destructive/80' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        Trash
                    </a>
                </div>
                <x-ui.button href="{{ route('companies.create') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M12 5v14"/><path d="M5 12h14"/>
                    </svg>
                    New Company
                </x-ui.button>
            </div>
        </div>
    </section>

    <section class="data-shell">
        <div class="section-header">
            <div>
                <div class="section-kicker">Filters</div>
                <h2 class="section-title">Refine your business list</h2>
                <p class="section-copy">Search by name, code, or email.</p>
            </div>
        </div>

        <form method="GET" class="data-toolbar items-center lg:grid-cols-[minmax(0,1.3fr)_repeat(1,minmax(0,0.7fr))_auto]" data-async-search data-target="#companies-results" action="{{ route('companies.index') }}">
            <input type="hidden" name="status" value="{{ request('status', 'active') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, code or email..." class="ui-input">
            
            <select name="per_page" class="ui-select">
                @foreach([5, 10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }} per page</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                @if(request()->anyFilled(['search']) || request('status') === 'deleted')
                    <a href="{{ route('companies.index') }}" class="ui-button-secondary h-11 px-4 rounded-xl flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <div id="companies-results">
            @include('companies.partials.results')
        </div>
    </section>
</div>
@endsection
