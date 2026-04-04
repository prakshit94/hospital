@extends('layouts.app')

@php
    $pageTitle = 'Profile';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <span class="hero-kicker">Personal Workspace</span>
            <h1 class="hero-title">Profile settings</h1>
            <p class="hero-copy">Manage your own identity details, role snapshot, and password inside the upgraded premium settings experience.</p>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
            <x-ui.card class="space-y-6">
                <div>
                    <div class="section-kicker">Personal Information</div>
                    <h2 class="section-title">Update your identity</h2>
                    <p class="section-copy">This updates your display identity across the dashboard and audit logs.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="ui-field">
                            <label for="name" class="ui-label">Full Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="ui-input" required>
                        </div>
                        <div class="ui-field">
                            <label for="email" class="ui-label">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="ui-input" required>
                        </div>
                    </div>

                    <x-ui.button>Save Profile</x-ui.button>
                </form>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Access Snapshot</div>
                    <h2 class="section-title">Current role context</h2>
                    <p class="section-copy">Your roles and effective presence inside the RBAC workspace.</p>
                </div>

                <div class="space-y-4">
                    <div class="detail-tile">
                        <div class="detail-label">Roles</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <span class="ui-chip">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Last Login</div>
                        <div class="detail-value">{{ $user->last_login_at?->format('d M Y h:i A') ?? 'Never' }}</div>
                    </div>
                </div>
            </x-ui.card>
        </section>

        <x-ui.card class="space-y-6">
            <div>
                <div class="section-kicker">Credential Security</div>
                <h2 class="section-title">Change password</h2>
                <p class="section-copy">Use your current password to set a new one.</p>
            </div>

            <form method="POST" action="{{ route('profile.password.update') }}" class="grid gap-4 md:grid-cols-3">
                @csrf
                @method('PUT')

                <div class="ui-field">
                    <label for="current_password" class="ui-label">Current Password</label>
                    <input id="current_password" name="current_password" type="password" class="ui-input" required>
                </div>
                <div class="ui-field">
                    <label for="password" class="ui-label">New Password</label>
                    <input id="password" name="password" type="password" class="ui-input" required>
                </div>
                <div class="ui-field">
                    <label for="password_confirmation" class="ui-label">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="ui-input" required>
                </div>

                <div class="md:col-span-3">
                    <x-ui.button>Update Password</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
