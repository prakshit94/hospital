@extends('layouts.app')

@php
    $pageTitle = 'Record Details';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="mb-3 inline-flex rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.2em] text-primary">Live record</div>
                <h1 class="font-heading text-3xl font-black tracking-tight">North Distribution Hub</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">Use this detail screen for profiles, warehouses, suppliers, projects, or any record with structured metadata.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="secondary">Edit</x-ui.button>
                <x-ui.button>Primary Action</x-ui.button>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-ui.card class="xl:col-span-2">
                <div class="mb-6">
                    <h2 class="font-heading text-xl font-bold">Overview</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Use cards like this to group key metadata and operational context.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @foreach([
                        ['label' => 'Code', 'value' => 'WH-019'],
                        ['label' => 'Manager', 'value' => 'Ritika Sharma'],
                        ['label' => 'Region', 'value' => 'North Cluster'],
                        ['label' => 'Status', 'value' => 'Active'],
                        ['label' => 'Created', 'value' => '03 Apr 2026'],
                        ['label' => 'Capacity', 'value' => '18,000 units'],
                    ] as $item)
                        <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4">
                            <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">{{ $item['label'] }}</div>
                            <div class="mt-2 text-sm font-semibold text-foreground">{{ $item['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <h2 class="font-heading text-xl font-bold">Quick Notes</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Use side panels for summaries and side actions.</p>
                </div>

                <div class="rounded-2xl border border-border/60 bg-secondary/25 p-4 text-sm text-muted-foreground">
                    This starter keeps the visual shell advanced while staying generic enough to reuse across projects.
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection

