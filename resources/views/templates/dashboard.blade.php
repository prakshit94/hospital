@extends('layouts.app')

@php
    $pageTitle = 'Dashboard';
    $stats = $stats ?? [
        ['label' => 'Revenue', 'value' => 'Rs 2,48,000', 'change' => '+12.4%', 'trend' => 'up'],
        ['label' => 'Orders', 'value' => '1,284', 'change' => '+6.8%', 'trend' => 'up'],
        ['label' => 'Customers', 'value' => '864', 'change' => '+4.1%', 'trend' => 'up'],
        ['label' => 'Refunds', 'value' => '38', 'change' => '-1.2%', 'trend' => 'down'],
    ];
    $activities = $activities ?? [
        ['title' => 'North region order batch cleared', 'meta' => '3 minutes ago'],
        ['title' => 'New enterprise customer onboarded', 'meta' => '14 minutes ago'],
        ['title' => 'Weekly payout report exported', 'meta' => '42 minutes ago'],
    ];
    $orders = $orders ?? [
        ['number' => 'ORD-2401', 'customer' => 'Mehta Agro', 'amount' => 'Rs 24,500', 'status' => 'Processing'],
        ['number' => 'ORD-2402', 'customer' => 'FieldCart Supply', 'amount' => 'Rs 12,200', 'status' => 'Packed'],
        ['number' => 'ORD-2403', 'customer' => 'Rural Hub', 'amount' => 'Rs 7,450', 'status' => 'Delivered'],
    ];
@endphp

@section('content')
    <div class="space-y-8 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Operational Command Center</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">A reusable Laravel 12 dashboard shell with expressive cards, strong hierarchy, and portable Blade structure.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="secondary">Export Snapshot</x-ui.button>
                <x-ui.button>Create Record</x-ui.button>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach($stats as $stat)
                <x-ui.card class="relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1 {{ $stat['trend'] === 'up' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                    <div class="space-y-3">
                        <div class="text-xs font-black uppercase tracking-[0.22em] text-muted-foreground">{{ $stat['label'] }}</div>
                        <div class="font-heading text-3xl font-black tracking-tight">{{ $stat['value'] }}</div>
                        <div class="text-xs font-bold {{ $stat['trend'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $stat['change'] }} vs previous period
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-ui.card class="xl:col-span-2">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="font-heading text-xl font-bold">Revenue Momentum</h2>
                        <p class="text-sm text-muted-foreground">Use this card for charts, BI widgets, or KPI visualizations.</p>
                    </div>
                    <span class="rounded-xl bg-secondary px-3 py-2 text-xs font-black uppercase tracking-[0.2em] text-muted-foreground">This Month</span>
                </div>

                <div class="h-72 rounded-[2rem] border border-dashed border-border/80 bg-gradient-to-br from-primary/6 via-transparent to-sky-500/6 p-6">
                    <div class="flex h-full items-end justify-between gap-4">
                        @foreach([28, 46, 38, 64, 52, 74, 68, 88, 72, 94, 84, 100] as $height)
                            <div class="flex flex-1 flex-col items-center justify-end gap-3">
                                <div class="w-full rounded-t-2xl bg-gradient-to-t from-primary via-sky-500 to-indigo-500" style="height: {{ $height }}%;"></div>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">{{ $loop->iteration }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="mb-5">
                    <h2 class="font-heading text-xl font-bold">Activity Feed</h2>
                    <p class="text-sm text-muted-foreground">Short operational events work well here.</p>
                </div>
                <div class="space-y-3">
                    @foreach($activities as $activity)
                        <div class="rounded-2xl border border-border/60 bg-secondary/35 p-4">
                            <div class="text-sm font-semibold text-foreground">{{ $activity['title'] }}</div>
                            <div class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">{{ $activity['meta'] }}</div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <x-ui.card>
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="font-heading text-xl font-bold">Recent Orders</h2>
                    <p class="text-sm text-muted-foreground">Portable table styling for admin pages.</p>
                </div>
                <x-ui.button variant="ghost" href="/orders">View all</x-ui.button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="border-b border-border/60 text-[11px] font-black uppercase tracking-[0.22em] text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Order</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @foreach($orders as $order)
                            <tr class="transition hover:bg-secondary/30">
                                <td class="px-4 py-4 font-bold text-foreground">{{ $order['number'] }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $order['customer'] }}</td>
                                <td class="px-4 py-4 text-sm font-semibold">{{ $order['amount'] }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $order['status'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
@endsection

