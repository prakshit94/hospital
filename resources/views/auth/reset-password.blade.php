@extends('layouts.guest')

@php
    $pageTitle = 'Set New Password';
@endphp

@section('content')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="mx-auto w-full max-w-md">
            <div class="rounded-[2rem] border border-white/20 bg-white/80 p-8 shadow-2xl backdrop-blur-xl dark:bg-zinc-900/80 dark:ring-1 dark:ring-white/10">
                <div class="mb-8 text-center">
                    <h1 class="font-heading text-3xl font-black tracking-tight">Choose a new password</h1>
                    <p class="mt-2 text-sm text-muted-foreground">Enter the reset token details and set a fresh password.</p>
                </div>

                <form class="space-y-5" method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-foreground">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required autofocus>
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-foreground">New password</label>
                        <input id="password" name="password" type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-foreground">Confirm new password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <x-ui.button class="w-full justify-center">Reset password</x-ui.button>
                </form>
            </div>
        </div>
    </div>
@endsection
