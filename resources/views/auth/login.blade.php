@extends('layouts.guest')

@php
    $pageTitle = 'Sign In';
@endphp

@section('content')
    <div class="auth-shell">
        <div class="auth-spotlight">
            <div class="auth-spotlight-panel">
                <span class="hero-kicker">AccessHub</span>
                <h1 class="hero-title max-w-lg">Simple access control for your team.</h1>
                <p class="hero-copy max-w-xl">Manage users, roles, permissions, reports, and audit logs in one place.</p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="auth-feature">
                        <div class="section-kicker">Users</div>
                        <div class="mt-2 text-lg font-semibold text-foreground">Account management</div>
                        <p class="mt-2 text-sm text-muted-foreground">Create, update, and review account access.</p>
                    </div>
                    <div class="auth-feature">
                        <div class="section-kicker">Audit</div>
                        <div class="mt-2 text-lg font-semibold text-foreground">Activity tracking</div>
                        <p class="mt-2 text-sm text-muted-foreground">Review changes and sign-ins from the same workspace.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="auth-card">
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-[1.75rem] bg-gradient-to-br from-primary to-sky-500 text-white shadow-xl shadow-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3"></path>
                        </svg>
                    </div>
                    <h1 class="font-heading text-3xl font-black tracking-tight">AccessHub</h1>
                    <p class="mt-2 text-sm text-muted-foreground">Sign in to manage users, roles, permissions, and API access.</p>
                </div>

                @if($errors->any())
                    <div class="mb-5 rounded-[1.5rem] border border-rose-500/15 bg-rose-500/10 px-4 py-3 text-sm text-rose-700 dark:text-rose-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form class="space-y-5" method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="ui-field">
                        <label for="email" class="ui-label">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', 'admin@example.com') }}" class="ui-input" placeholder="you@example.com" required autofocus>
                    </div>

                    <div class="ui-field">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="password" class="ui-label !mb-0">Password</label>
                            <a href="{{ route('password.request') }}" class="text-[11px] font-black uppercase tracking-[0.18em] text-primary">Forgot password?</a>
                        </div>
                        <input id="password" name="password" type="password" class="ui-input" placeholder="Enter your password" required>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label class="flex items-center gap-3 text-sm text-muted-foreground">
                            <input name="remember" type="checkbox" value="1" class="h-4 w-4" @checked(old('remember'))>
                            Remember me
                        </label>
                        <span class="ui-chip-muted">Web + API Ready</span>
                    </div>

                    <x-ui.button class="w-full justify-center">Sign In</x-ui.button>
                </form>

                <p class="mt-6 text-center text-xs font-black uppercase tracking-[0.2em] text-muted-foreground">
                    Admin login: admin@example.com / password
                </p>
            </div>
        </div>
    </div>
@endsection
