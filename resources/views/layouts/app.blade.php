@php
    $pageTitle = $pageTitle ?? config('enterprise-ui.app_name', config('app.name'));
    $hideSidebar = $hideSidebar ?? false;
@endphp
<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        sidebarCollapsed: localStorage.getItem('ui.sidebarCollapsed') === 'true',
        mobileMenuOpen: false,
        theme: localStorage.getItem('ui.theme') || 'system',
        colorTheme: localStorage.getItem('ui.colorTheme') || 'blue',
        get isDark() {
            return this.theme === 'dark' || (this.theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        },
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('ui.sidebarCollapsed', this.sidebarCollapsed);
        },
        setTheme(next) {
            this.theme = next;
            localStorage.setItem('ui.theme', next);
        },
        setColorTheme(next) {
            this.colorTheme = next;
            localStorage.setItem('ui.colorTheme', next);
        }
    }"
    :class="[isDark ? 'dark' : '', colorTheme ? 'theme-' + colorTheme : '']"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-svh overflow-x-hidden bg-background text-foreground transition-colors duration-300">
    <x-ui.flash />

    <div class="fixed inset-0 z-[-1] overflow-hidden bg-background">
        <div class="premium-grid absolute inset-0 opacity-70 [mask-image:radial-gradient(ellipse_68%_54%_at_50%_10%,#000_68%,transparent_100%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.45),transparent_40%)] dark:bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.06),transparent_34%)]"></div>
        <div class="absolute left-[-8rem] top-[-8rem] h-[24rem] w-[24rem] rounded-full bg-primary/20 blur-[110px]"></div>
        <div class="absolute right-[-4rem] top-[4rem] h-[22rem] w-[22rem] rounded-full bg-sky-400/20 blur-[120px]"></div>
        <div class="absolute bottom-[-8rem] left-[28%] h-[20rem] w-[20rem] rounded-full bg-rose-300/15 blur-[120px] dark:bg-fuchsia-500/10"></div>
    </div>

    @unless($hideSidebar)
        <x-layout.app-sidebar />
    @endunless

    <div class="relative min-h-svh flex flex-col transition-all duration-300 {{ $hideSidebar ? '' : 'md:pl-72' }}"
        :class="sidebarCollapsed && !{{ $hideSidebar ? 'true' : 'false' }} ? 'md:!pl-[4.5rem]' : ''">
        <x-layout.header :page-title="$pageTitle" :hide-sidebar-toggle="$hideSidebar" />

        <main class="flex-1 pb-8">
            @yield('content')
        </main>

        <footer class="mx-4 mt-auto rounded-[1.75rem] border border-white/35 bg-white/45 px-6 py-5 text-center text-xs text-muted-foreground shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)] backdrop-blur-2xl dark:border-white/8 dark:bg-white/5 md:mx-6">
            {{ config('enterprise-ui.app_name', config('app.name')) }} premium workspace experience
        </footer>
    </div>
</body>
</html>
