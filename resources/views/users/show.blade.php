@extends('layouts.app')

@php
    $pageTitle = 'User Profile';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="flex flex-wrap gap-2 items-center mb-1">
                        <span class="{{ $user->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">{{ $user->status }} account</span>
                        @if($user->is_online)
                            <span class="ui-status-success">Online</span>
                        @else
                            <span class="ui-status-danger">Offline</span>
                        @endif
                    </div>
                    <h1 class="hero-title">{{ $user->name ?: trim("{$user->first_name} {$user->last_name}") }}</h1>
                    <p class="hero-copy">{{ $user->email }}</p>
                    <p class="hero-copy mt-1 uppercase tracking-widest text-xs font-bold text-muted-foreground">{{ $user->job_title }} {{ $user->department ? '- ' . $user->department : '' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if(auth()->user()?->hasPermission('users.update'))
                        <x-ui.button variant="secondary" href="{{ route('users.edit', $user) }}" data-modal-open>Edit</x-ui.button>
                    @endif
                    @if(auth()->user()?->hasPermission('users.delete') && auth()->id() !== $user->id)
                        <form method="POST" action="{{ route('users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <x-ui.button onclick="return confirm('Delete this user?')">Delete</x-ui.button>
                        </form>
                    @endif
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.8fr)]">
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">Account Overview</div>
                        <h2 class="section-title">Identity snapshot</h2>
                        <p class="section-copy">Roles, current state, and login history for this account.</p>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-tile">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">{{ $user->email }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Phone</div>
                        <div class="detail-value">{{ $user->phone ?: 'Not provided' }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">{{ $user->location ?: 'Not provided' }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Gender</div>
                        <div class="detail-value">{{ $user->gender ?: 'Not provided' }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Last Login</div>
                        <div class="detail-value">{{ $user->last_login_at?->format('d M Y h:i A') ?? 'Never' }}</div>
                    </div>
                    <div class="detail-tile md:col-span-2">
                        <div class="detail-label">Roles</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <span class="ui-chip">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Effective Access</div>
                    <h2 class="section-title">Permission map</h2>
                    <p class="section-copy">Permissions inherited from the attached roles.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->permissionSlugs() as $permission)
                        <span class="ui-chip-muted">{{ $permission }}</span>
                    @endforeach
                </div>
            </x-ui.card>
        </section>

        <x-ui.card>
            <div class="section-header">
                <div>
                    <div class="section-kicker">Recent Activity</div>
                    <h2 class="section-title">Latest events around this user</h2>
                    <p class="section-copy">Recent actions performed by or against this user.</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($activities as $activity)
                    <div class="list-card">
                        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div class="text-sm font-semibold text-foreground">{{ $activity->description ?: $activity->action }}</div>
                                <div class="mt-2"><span class="ui-chip">{{ $activity->action }}</span></div>
                            </div>
                            <div class="text-xs font-black uppercase tracking-[0.18em] text-muted-foreground">{{ $activity->created_at?->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No activity recorded yet.</div>
                @endforelse
            </div>
        </x-ui.card>
    </div>
@endsection
