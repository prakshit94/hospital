@extends('layouts.guest')

@php
    $pageTitle = 'Forgot Password';
@endphp

@section('content')
    <div class="auth-shell lg:grid-cols-[1fr_minmax(0,460px)]">
        <div class="auth-spotlight">
            <div class="auth-spotlight-panel">
                <span class="hero-kicker">Recovery Flow</span>
                <h1 class="hero-title max-w-lg">Secure password recovery with the upgraded premium auth experience.</h1>
                <p class="hero-copy max-w-xl">Request a reset link and move back into the workspace without leaving the polished visual system.</p>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="auth-card">
                <div class="mb-8 text-center">
                    <h1 class="font-heading text-3xl font-black tracking-tight">Reset your password</h1>
                    <p class="mt-2 text-sm text-muted-foreground">We will email a recovery link using Laravel's built-in password broker.</p>
                </div>

                @if (session('status'))
                    <div class="mb-5 rounded-[1.5rem] border border-emerald-500/15 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="space-y-5" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="ui-field">
                        <label for="email" class="ui-label">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="ui-input" required autofocus>
                    </div>

                    <x-ui.button class="w-full justify-center">Email Reset Link</x-ui.button>
                </form>

                <div class="mt-5 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-[0.18em] text-primary">Back to sign in</a>
                </div>
            </div>
        </div>
    </div>
@endsection
