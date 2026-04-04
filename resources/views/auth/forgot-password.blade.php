@extends('layouts.guest')

@php
    $pageTitle = 'Forgot Password';
@endphp

@section('content')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="mx-auto w-full max-w-md">
            <div class="rounded-[2rem] border border-white/20 bg-white/80 p-8 shadow-2xl backdrop-blur-xl dark:bg-zinc-900/80 dark:ring-1 dark:ring-white/10">
                <div class="mb-8 text-center">
                    <h1 class="font-heading text-3xl font-black tracking-tight">Reset your password</h1>
                    <p class="mt-2 text-sm text-muted-foreground">We’ll email a recovery link using Laravel’s built-in password broker.</p>
                </div>

                @if (session('status'))
                    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="space-y-5" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-foreground">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required autofocus>
                    </div>

                    <x-ui.button class="w-full justify-center">Email reset link</x-ui.button>
                </form>

                <div class="mt-5 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Back to sign in</a>
                </div>
            </div>
        </div>
    </div>
@endsection
