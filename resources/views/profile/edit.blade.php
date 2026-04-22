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

        <x-ui.card class="space-y-6">
            <div>
                <div class="section-kicker">Multi-Factor Authentication</div>
                <h2 class="section-title">Two-factor authentication</h2>
                <p class="section-copy">Add an extra layer of security to your account using a time-based one-time password (TOTP).</p>
            </div>

            <div class="space-y-6">
                @if (!$user->two_factor_secret)
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <x-ui.button>Enable 2FA</x-ui.button>
                    </form>
                @elseif (!$user->two_factor_confirmed_at)
                    <div class="rounded-xl border border-warning/20 bg-warning/5 p-6">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-4">
                                <h3 class="font-bold text-foreground">Finish enabling two-factor authentication</h3>
                                <p class="text-sm text-muted-foreground leading-relaxed">
                                    To finish enabling two-factor authentication, scan the following QR code using your phone's authenticator application (Google Authenticator, Authy, etc) and provide the generated TOTP code.
                                </p>

                                <div id="two-factor-qr-code" class="mt-4 inline-block rounded-lg bg-white p-2 shadow-inner" x-data="{ svg: '' }" x-init="fetch('{{ route('two-factor.qr-code') }}').then(r => r.json()).then(d => svg = d.svg)">
                                    <div x-html="svg"></div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('two-factor.confirm') }}" class="w-full max-w-sm space-y-4">
                                @csrf
                                <div class="ui-field">
                                    <label for="code" class="ui-label">Authentication Code</label>
                                    <input id="code" name="code" type="text" class="ui-input" placeholder="000000" required autofocus>
                                    @error('code')
                                        <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <x-ui.button class="w-full">Confirm 2FA</x-ui.button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl border border-success/20 bg-success/5 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-success">Two-factor authentication is enabled</h3>
                                <p class="mt-1 text-sm text-muted-foreground">Your account is now protected with an additional layer of security.</p>
                            </div>
                            <form method="POST" action="{{ route('two-factor.disable') }}">
                                @csrf
                                @method('DELETE')
                                <x-ui.button variant="danger">Disable 2FA</x-ui.button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </x-ui.card>
    </div>
@endsection
