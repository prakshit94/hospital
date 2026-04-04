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
    <div class="fixed inset-0 z-[-1] bg-[#fafafa] dark:bg-[#09090b]">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
        <div class="absolute top-[-12rem] left-[8%] h-72 w-72 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="absolute right-[8%] top-0 h-80 w-80 rounded-full bg-sky-500/10 blur-3xl"></div>
    </div>

    @unless($hideSidebar)
        <x-layout.app-sidebar />
    @endunless

    <div class="relative min-h-svh flex flex-col transition-all duration-300 {{ $hideSidebar ? '' : 'md:pl-72' }}"
        :class="sidebarCollapsed && !{{ $hideSidebar ? 'true' : 'false' }} ? 'md:!pl-[4.5rem]' : ''">
        <x-layout.header :page-title="$pageTitle" :hide-sidebar-toggle="$hideSidebar" />

        <main class="flex-1">
            <x-ui.flash />
            @yield('content')
        </main>

        <footer class="border-t border-border/50 px-6 py-5 text-center text-xs text-muted-foreground">
            {{ config('enterprise-ui.app_name', config('app.name')) }} UI Starter
        </footer>
    </div>
</body>
</html>
