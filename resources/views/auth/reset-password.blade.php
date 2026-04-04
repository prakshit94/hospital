@extends('layouts.guest')

@php
    $pageTitle = 'Set New Password';
@endphp

@section('content')
    <div class="auth-shell lg:grid-cols-[1fr_minmax(0,460px)]">
        <div class="auth-spotlight">
            <div class="auth-spotlight-panel">
                <span class="hero-kicker">Credential Refresh</span>
                <h1 class="hero-title max-w-lg">Set a new password and jump back into the premium workspace.</h1>
                <p class="hero-copy max-w-xl">Confirm the reset request details and create a fresh password for your account.</p>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="auth-card">
                <div class="mb-8 text-center">
                    <h1 class="font-heading text-3xl font-black tracking-tight">Choose a new password</h1>
                    <p class="mt-2 text-sm text-muted-foreground">Enter the reset token details and set a fresh password.</p>
                </div>

                <form class="space-y-5" method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="ui-field">
                        <label for="email" class="ui-label">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" class="ui-input" required autofocus>
                    </div>

                    <div class="ui-field">
                        <label for="password" class="ui-label">New Password</label>
                        <input id="password" name="password" type="password" class="ui-input" required>
                    </div>

                    <div class="ui-field">
                        <label for="password_confirmation" class="ui-label">Confirm New Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="ui-input" required>
                    </div>

                    <x-ui.button class="w-full justify-center">Reset Password</x-ui.button>
                </form>
            </div>
        </div>
    </div>
@endsection
