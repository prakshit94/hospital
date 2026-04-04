@extends('layouts.guest')

@php
    $pageTitle = 'Sign In';
@endphp

@section('content')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="mx-auto w-full max-w-md">
            <div class="mb-8 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-[1.75rem] bg-gradient-to-br from-primary to-sky-500 text-white shadow-xl shadow-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3"></path>
                    </svg>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/20 bg-white/80 p-8 shadow-2xl backdrop-blur-xl dark:bg-zinc-900/80 dark:ring-1 dark:ring-white/10">
                <div class="mb-8 text-center">
                    <h1 class="font-heading text-3xl font-black tracking-tight">AccessHub</h1>
                    <p class="mt-2 text-sm text-muted-foreground">Sign in to manage users, roles, permissions, and API access.</p>
                </div>

                @if($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form class="space-y-5" method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-foreground">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', 'admin@example.com') }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" placeholder="you@example.com" required autofocus>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-foreground">Password</label>
                            <span class="text-xs font-bold uppercase tracking-[0.16em] text-primary">Default seed: password</span>
                        </div>
                        <input id="password" name="password" type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" placeholder="Enter your password" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-3 text-sm text-muted-foreground">
                            <input name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-border text-primary focus:ring-primary" @checked(old('remember'))>
                            Remember me
                        </label>
                        <span class="rounded-xl bg-secondary px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.18em] text-muted-foreground">Web + API Ready</span>
                    </div>

                    <x-ui.button class="w-full justify-center">Sign in</x-ui.button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs font-bold uppercase tracking-[0.2em] text-muted-foreground">
                Admin login: admin@example.com / password
            </p>
        </div>
    </div>
@endsection
