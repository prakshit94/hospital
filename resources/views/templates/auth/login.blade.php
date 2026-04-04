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
                    <h1 class="font-heading text-3xl font-black tracking-tight">Welcome back</h1>
                    <p class="mt-2 text-sm text-muted-foreground">Drop this guest template into any Laravel 12 authentication flow.</p>
                </div>

                <form class="space-y-5" method="POST" action="#">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-foreground">Email address</label>
                        <input type="email" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" placeholder="you@example.com">
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="block text-sm font-semibold text-foreground">Password</label>
                            <a href="#" class="text-xs font-bold uppercase tracking-[0.16em] text-primary">Reset</a>
                        </div>
                        <input type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" placeholder="Enter your password">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-3 text-sm text-muted-foreground">
                            <input type="checkbox" class="h-4 w-4 rounded border-border text-primary focus:ring-primary">
                            Remember me
                        </label>
                        <span class="rounded-xl bg-secondary px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.18em] text-muted-foreground">Secure</span>
                    </div>

                    <x-ui.button class="w-full justify-center">Sign in</x-ui.button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs font-bold uppercase tracking-[0.2em] text-muted-foreground">
                Protected by enterprise-grade security
            </p>
        </div>
    </div>
@endsection

