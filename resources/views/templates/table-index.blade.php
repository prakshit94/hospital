@extends('layouts.app')

@php
    $pageTitle = 'Resource Index';
    $rows = $rows ?? [
        ['name' => 'North Warehouse', 'code' => 'WH-01', 'owner' => 'A. Mehta', 'status' => 'Active'],
        ['name' => 'Field Ops Team', 'code' => 'TEAM-02', 'owner' => 'R. Singh', 'status' => 'Pending'],
        ['name' => 'Retail Cluster', 'code' => 'CL-03', 'owner' => 'K. Shah', 'status' => 'Archived'],
    ];
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Resource Index</h1>
                <p class="mt-2 text-sm text-muted-foreground">Generic index page template for admin modules, catalogs, teams, or operational records.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="secondary">Export</x-ui.button>
                <x-ui.button>Create New</x-ui.button>
            </div>
        </div>

        <x-ui.card>
            <div class="mb-5 grid gap-3 lg:grid-cols-[1fr_auto_auto]">
                <input type="text" placeholder="Search records..." class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                <select class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    <option>All statuses</option>
                    <option>Active</option>
                    <option>Pending</option>
                    <option>Archived</option>
                </select>
                <select class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    <option>Newest first</option>
                    <option>Oldest first</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="border-b border-border/60 text-[11px] font-black uppercase tracking-[0.22em] text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Code</th>
                            <th class="px-4 py-3">Owner</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @foreach($rows as $row)
                            <tr class="transition hover:bg-secondary/25">
                                <td class="px-4 py-4 font-semibold text-foreground">{{ $row['name'] }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $row['code'] }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $row['owner'] }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $row['status'] }}</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <x-ui.button variant="ghost" href="#">View</x-ui.button>
                                        <x-ui.button variant="secondary" href="#">Edit</x-ui.button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
@endsection

