@extends('layouts.app')

@php
    $pageTitle = 'Security & Devices';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <span class="hero-kicker">Security Center</span>
            <h1 class="hero-title">My Devices</h1>
            <p class="hero-copy">Manage and monitor the devices currently signed into your account. Revoke access for any unrecognized sessions.</p>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(320px,0.4fr)]">
            <div class="space-y-6">
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach($devices as $device)
                        <x-ui.card class="relative overflow-hidden group">
                            @if($device->device_id === $currentDeviceId)
                                <div class="absolute top-0 right-0">
                                    <div class="bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-bl-xl border-l border-b border-primary/20">
                                        This Device
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-border bg-secondary/50 text-muted-foreground group-hover:bg-primary/10 group-hover:text-primary transition-colors duration-300">
                                    @if(str_contains(strtolower($device->platform), 'windows'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12.002 12 12.001 12 2 22 3zM11 12.002 2 12 2 4.409 11 3zM2 12.001l9 .001V21l-9-1.409zM12 12.001l10 .001V22l-10-1z"/></svg>
                                    @elseif(str_contains(strtolower($device->platform), 'mac') || str_contains(strtolower($device->platform), 'ios'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20.94c1.88 0 3.05-1.12 3.05-1.12s1.17 1.12 3.05 1.12c2.11 0 3.9-1.14 3.9-4.7 0-3.56-1.79-4.7-3.9-4.7-1.88 0-3.05 1.12-3.05 1.12s-1.17-1.12-3.05-1.12c-2.11 0-3.9 1.14-3.9 4.7 0 3.56 1.79 4.7 3.9 4.7zM12 9.4c0-1.6 1.3-2.9 2.9-2.9 1.6 0 2.9 1.3 2.9 2.9s-1.3 2.9-2.9 2.9c-1.6 0-2.9-1.3-2.9-2.9z"/></svg>
                                    @elseif(str_contains(strtolower($device->platform), 'linux'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0 1 10 10 10 10 0 0 1-10 10A10 10 0 0 1 2 12 10 10 0 0 1 12 2z"/><path d="M12 7v5l3 3"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><line x1="12" x2="12.01" y1="18" y2="18"/></svg>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-bold text-foreground truncate">{{ $device->browser }} on {{ $device->platform }}</div>
                                    <div class="mt-1 text-xs text-muted-foreground font-mono">{{ $device->ip_address }}</div>
                                    
                                    <div class="mt-4 flex items-center gap-3">
                                        <div class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground/60">
                                            Last Active: <span class="text-foreground">{{ $device->last_active_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between border-t border-border/50 pt-4">
                                <div class="flex items-center gap-2">
                                    @if($device->is_trusted)
                                        <span class="ui-chip bg-emerald-500/10 text-emerald-600 border-emerald-500/20">Trusted</span>
                                    @else
                                        <span class="ui-chip-muted">Temporary</span>
                                    @endif
                                </div>

                                @if($device->device_id !== $currentDeviceId)
                                    <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('Revoke access for this device?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-black uppercase tracking-widest text-rose-500 hover:text-rose-600 transition-colors">Revoke</button>
                                    </form>
                                @endif
                            </div>
                        </x-ui.card>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <x-ui.card class="bg-primary/5 border-primary/20">
                    <div class="section-kicker">Security Tip</div>
                    <h3 class="mt-2 font-bold text-foreground">Protect your session</h3>
                    <p class="mt-2 text-sm text-muted-foreground leading-relaxed">
                        If you see a device you don't recognize, revoke its access immediately and change your password. We also recommend enabling Two-Factor Authentication for maximum security.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-primary hover:underline">
                            Go to Security Settings
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </div>
                </x-ui.card>
            </div>
        </section>
    </div>
@endsection
