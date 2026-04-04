@extends('layouts.app')

@php
    $pageTitle = 'Profile';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div>
            <h1 class="font-heading text-3xl font-black tracking-tight">Profile Settings</h1>
            <p class="mt-2 text-sm text-muted-foreground">Manage your own identity details, assigned access, and password.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-ui.card class="space-y-6 xl:col-span-2">
                <div>
                    <h2 class="font-heading text-xl font-bold">Personal Information</h2>
                    <p class="mt-1 text-sm text-muted-foreground">This updates your display identity across the dashboard and audit logs.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold">Full name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
                        </div>
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
                        </div>
                    </div>

                    <x-ui.button>Save profile</x-ui.button>
                </form>
            </x-ui.card>

            <x-ui.card class="space-y-4">
                <div>
                    <h2 class="font-heading text-xl font-bold">Access Snapshot</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Your roles and effective permissions from RBAC.</p>
                </div>

                <div class="space-y-3">
                    <div>
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">Roles</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <span class="rounded-xl bg-primary/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-primary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground">Last Login</div>
                        <div class="mt-2 text-sm font-semibold text-foreground">{{ $user->last_login_at?->format('d M Y h:i A') ?? 'Never' }}</div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <x-ui.card class="space-y-6">
            <div>
                <h2 class="font-heading text-xl font-bold">Change Password</h2>
                <p class="mt-1 text-sm text-muted-foreground">Use your current password to set a new one.</p>
            </div>

            <form method="POST" action="{{ route('profile.password.update') }}" class="grid gap-4 md:grid-cols-3">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-2 block text-sm font-semibold">Current password</label>
                    <input id="current_password" name="current_password" type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold">New password</label>
                    <input id="password" name="password" type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
                </div>
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
                </div>

                <div class="md:col-span-3">
                    <x-ui.button>Update password</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
