@extends('layouts.app')

@php
    $pageTitle = 'Activity Logs';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div>
            <h1 class="font-heading text-3xl font-black tracking-tight">Activity Logs</h1>
            <p class="mt-2 text-sm text-muted-foreground">Audit trail for sign-ins, API token usage, and CRUD operations across the system.</p>
        </div>

        <x-ui.card>
            <form method="GET" class="mb-5 grid gap-3 lg:grid-cols-[1fr_auto]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activity..." class="rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                <x-ui.button class="justify-center">Filter</x-ui.button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="border-b border-border/60 text-[11px] font-black uppercase tracking-[0.22em] text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Action</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3">Actor</th>
                            <th class="px-4 py-3">IP</th>
                            <th class="px-4 py-3">When</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($activities as $activity)
                            <tr class="transition hover:bg-secondary/25">
                                <td class="px-4 py-4">
                                    <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $activity->action }}</span>
                                </td>
                                <td class="px-4 py-4 text-sm text-foreground">{{ $activity->description ?: 'No description' }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $activity->causer?->name ?? 'System' }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $activity->ip_address ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-sm text-muted-foreground">{{ $activity->created_at?->format('d M Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-muted-foreground">No activity recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $activities->links() }}</div>
        </x-ui.card>
    </div>
@endsection
