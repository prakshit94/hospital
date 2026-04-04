@extends('layouts.app')

@php
    $pageTitle = 'Permission Details';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="ui-chip-muted">{{ $permission->group_name }}</span>
                    <h1 class="hero-title">{{ $permission->name }}</h1>
                    <p class="hero-copy">{{ $permission->description ?: 'No description provided for this permission.' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if(auth()->user()?->hasPermission('permissions.update'))
                        <x-ui.button variant="secondary" href="{{ route('permissions.edit', $permission) }}" data-modal-open>Edit</x-ui.button>
                    @endif
                    @if(auth()->user()?->hasPermission('permissions.delete'))
                        <form method="POST" action="{{ route('permissions.destroy', $permission) }}">
                            @csrf
                            @method('DELETE')
                            <x-ui.button onclick="return confirm('Delete this permission?')">Delete</x-ui.button>
                        </form>
                    @endif
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Permission Metadata</div>
                        <h2 class="section-title">Operational details</h2>
                        <p class="section-copy">Core metadata and the roles that currently grant this ability.</p>
                    </div>
                </div>
                <div class="detail-grid">
                    <div class="detail-tile">
                        <div class="detail-label">Slug</div>
                        <div class="detail-value">{{ $permission->slug }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Group</div>
                        <div class="detail-value">{{ $permission->group_name }}</div>
                    </div>
                    <div class="detail-tile md:col-span-2">
                        <div class="detail-label">Roles Using This Permission</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($permission->roles as $role)
                                <span class="ui-chip">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Recent Activity</div>
                    <h2 class="section-title">Change history</h2>
                    <p class="section-copy">Recent changes affecting this permission.</p>
                </div>
                <div class="space-y-3">
                    @forelse($activities as $activity)
                        <div class="list-card text-sm">
                            <div class="font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                            <div class="mt-1 text-xs text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="empty-state">No activity recorded for this permission.</div>
                    @endforelse
                </div>
            </x-ui.card>
        </section>
    </div>
@endsection
