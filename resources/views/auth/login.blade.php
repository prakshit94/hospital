@extends('layouts.guest')

@php
    $pageTitle = 'Sign In | Divit Hospital';
@endphp

@section('content')
<div class="min-h-screen bg-slate-50 font-['Manrope'] selection:bg-emerald-100 selection:text-emerald-900">

    <div class="flex min-h-screen">
        {{-- LEFT PANEL: Brand Experience --}}
        <div class="hidden lg:flex w-[45%] relative overflow-hidden bg-slate-900">
            {{-- Dynamic Background Layer --}}
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 via-slate-900 to-slate-950"></div>
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-emerald-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-500/10 rounded-full blur-[120px]"></div>

            <div class="relative z-10 p-16 flex flex-col justify-between w-full">
                <div>
                    {{-- Logo Container --}}
                    <div class="mb-12 inline-flex items-center gap-6">
                        <div class="h-20 w-20 bg-white rounded-[24px] p-3 shadow-2xl shadow-emerald-500/30 flex items-center justify-center">
                            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-full w-full object-contain">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white font-['Sora'] font-extrabold text-xl tracking-tight">Divit Hospital</span>
                            <span class="text-emerald-400 text-[10px] uppercase tracking-[0.2em] font-bold">AccessHub Premium</span>
                        </div>
                    </div>

                    <h1 class="text-5xl font-extrabold text-white leading-[1.1] mb-8 font-['Sora']">
                        Secure Medical & <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">
                            Compliance Workspace
                        </span>
                    </h1>

                    <p class="text-slate-400 text-lg max-w-md leading-relaxed mb-12">
                        Advanced health record management and statutory compliance tracking for the modern healthcare enterprise.
                    </p>

                    {{-- Feature Highlights --}}
                    <div class="space-y-6">
                        <div class="flex items-start gap-4 group">
                            <div class="mt-1 h-8 w-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-base">Certified Security</h3>
                                <p class="text-slate-500 text-sm">HIPAA & GDPR compliant data encryption protocols.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 group">
                            <div class="mt-1 h-8 w-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-base">Statutory Reporting</h3>
                                <p class="text-slate-500 text-sm">Automated Form 32 & 33 generation for Factories Act compliance.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-slate-500 text-xs font-medium">
                    © {{ date('Y') }} Divit Hospital Systems · Version 4.0
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL: Login Form --}}
        <div class="flex-1 flex items-center justify-center p-8 bg-slate-50">
            <div class="w-full max-w-[440px] space-y-8">
                
                <div class="bg-white p-10 rounded-[40px] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-slate-100 relative overflow-hidden">
                    {{-- Decorative Blur --}}
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-50 rounded-full blur-3xl opacity-50"></div>
                    
                    <div class="relative z-10">
                        <div class="text-center mb-10">
                            <div class="mx-auto mb-6 h-24 w-24 bg-white rounded-[28px] p-4 shadow-xl flex items-center justify-center border border-slate-100">
                                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-full w-full object-contain">
                            </div>
                            <h2 class="text-3xl font-extrabold text-slate-900 font-['Sora'] tracking-tight">Welcome Back</h2>
                            <p class="text-slate-500 mt-2 text-sm">Please enter your professional credentials</p>
                        </div>

                        {{-- ERROR MESSAGES --}}
                        @if($errors->any())
                            <div class="mb-6 animate-shake p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-sm flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                            @csrf

                            <div class="space-y-2">
                                <label for="email" class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Work Email</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                        </svg>
                                    </div>
                                    <input id="email" name="email" type="email" value="{{ old('email', request()->cookie('remembered_email')) }}" required autofocus
                                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 text-sm focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 outline-none"
                                        placeholder="admin@divit.hospital">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between items-center px-1">
                                    <label for="password" class="text-xs font-bold text-slate-400 uppercase tracking-widest">Security Pin</label>
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors">Recover?</a>
                                </div>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" name="password" type="password" required
                                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 text-sm focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 outline-none"
                                        placeholder="••••••••">
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="remember" id="remember" value="1" @checked(old('remember')) class="peer h-5 w-5 opacity-0 absolute cursor-pointer">
                                        <div class="h-5 w-5 border-2 border-slate-200 rounded-lg peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all"></div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white absolute left-0.5.5 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none ml-[3px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-500 group-hover:text-slate-700 transition-colors">Keep me signed in</span>
                                </label>
                            </div>

                            <button type="submit" 
                                class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-base shadow-xl shadow-slate-900/20 hover:bg-emerald-600 hover:shadow-emerald-500/30 active:scale-[0.98] transition-all duration-300">
                                Sign In to Workspace
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-slate-400 text-sm">Need technical assistance? <a href="#" class="text-slate-600 font-bold underline underline-offset-4 decoration-slate-200 hover:decoration-emerald-500 transition-all">Contact IT Support</a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- COMPLIANCE SECTION --}}
    <div class="max-w-7xl mx-auto px-8 py-24">
        <div class="grid lg:grid-cols-3 gap-12 items-center">
            <div class="lg:col-span-1">
                <span class="text-emerald-600 font-bold text-sm uppercase tracking-[0.2em] mb-4 block">Regulatory Framework</span>
                <h2 class="text-4xl font-black text-slate-900 font-['Sora'] leading-tight mb-6">
                    Factories Act, 1948 Compliance
                </h2>
                <p class="text-slate-500 leading-relaxed text-lg mb-8">
                    Fully automated statutory record management for healthcare and industrial safety regulations.
                </p>
                <div class="flex items-center gap-6">
                    <div class="h-1px flex-1 bg-slate-200"></div>
                </div>
            </div>

            <div class="lg:col-span-2 grid md:grid-cols-2 gap-8">
                {{-- FORM 32 CARD --}}
                <div class="group bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-500">
                    <div class="h-14 w-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-4 font-['Sora']">Form No. 32</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">Health Surveillance and Medical Examination records for high-risk industrial environments.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-xs font-bold text-slate-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Periodic Monitoring
                        </li>
                        <li class="flex items-center gap-2 text-xs font-bold text-slate-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Exposure Tracking
                        </li>
                    </ul>
                </div>

                {{-- FORM 33 CARD --}}
                <div class="group bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-500">
                    <div class="h-14 w-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-4 font-['Sora']">Form No. 33</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">Certificate of Fitness for employees working in dangerous operations and processes.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-xs font-bold text-slate-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Fitness Validation
                        </li>
                        <li class="flex items-center gap-2 text-xs font-bold text-slate-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Doctor Certification
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.4s ease-in-out 0s 2;
    }
</style>
@endsection