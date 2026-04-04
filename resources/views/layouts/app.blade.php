@php
    $pageTitle = $pageTitle ?? config('enterprise-ui.app_name', config('app.name'));
    $hideSidebar = $hideSidebar ?? false;
@endphp
<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        viewportWidth: window.innerWidth,
        sidebarCollapsed: window.innerWidth >= 1024 && localStorage.getItem('ui.sidebarCollapsed') === 'true',
        mobileMenuOpen: false,
        theme: localStorage.getItem('ui.theme') || 'system',
        colorTheme: localStorage.getItem('ui.colorTheme') || 'blue',
        get isDesktop() {
            return this.viewportWidth >= 1024;
        },
        get isTablet() {
            return this.viewportWidth >= 768 && this.viewportWidth < 1024;
        },
        get isDark() {
            return this.theme === 'dark' || (this.theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        },
        init() {
            this.handleResize();
            window.addEventListener('resize', () => this.handleResize());
        },
        handleResize() {
            this.viewportWidth = window.innerWidth;

            if (!this.isDesktop) {
                this.sidebarCollapsed = false;
            }

            if (this.viewportWidth >= 768) {
                this.mobileMenuOpen = false;
            }
        },
        toggleNavigation() {
            if (!this.isDesktop) {
                this.mobileMenuOpen = !this.mobileMenuOpen;
                return;
            }
            this.toggleSidebar();
        },
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('ui.sidebarCollapsed', this.sidebarCollapsed);
        },
        closeMobileMenu() {
            this.mobileMenuOpen = false;
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
    x-init="init()"
    :class="[isDark ? 'dark' : '', colorTheme ? 'theme-' + colorTheme : '']"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-svh overflow-x-hidden bg-background text-foreground transition-colors duration-300">
    <x-ui.flash />
    <div id="app-modal-root" class="fixed inset-0 z-[120] hidden">
        <div data-modal-backdrop class="absolute inset-0 bg-slate-950/45 backdrop-blur-sm"></div>
        <div class="relative flex min-h-full items-center justify-center p-4 sm:p-6">
            <div data-modal-panel class="relative w-full max-w-5xl overflow-hidden rounded-[1.5rem] border border-border bg-popover shadow-[0_30px_90px_-40px_rgba(15,23,42,0.4)]">
                <div data-modal-content class="max-h-[88vh] overflow-y-auto"></div>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-[-1] overflow-hidden bg-background">
        <div class="premium-grid absolute inset-0 opacity-25"></div>
    </div>

    @unless($hideSidebar)
        <x-layout.app-sidebar />
    @endunless

    @unless($hideSidebar)
        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition.opacity
            @click="closeMobileMenu()"
            class="fixed inset-0 z-40 bg-slate-950/45 backdrop-blur-sm md:hidden"
        ></div>
    @endunless

    <div class="relative min-h-svh flex flex-col transition-all duration-300"
        :class="{
            'lg:pl-72': !{{ $hideSidebar ? 'true' : 'false' }} && isDesktop && !sidebarCollapsed,
            'lg:pl-[4.75rem]': !{{ $hideSidebar ? 'true' : 'false' }} && isDesktop && sidebarCollapsed
        }">
        <x-layout.header :page-title="$pageTitle" :hide-sidebar-toggle="$hideSidebar" />

        <main class="page-shell">
            @yield('content')
        </main>

        <footer class="mx-4 mb-4 mt-auto rounded-[1.4rem] border border-border bg-card px-6 py-4 text-center text-xs font-medium text-muted-foreground shadow-[0_18px_40px_-32px_rgba(15,23,42,0.2)] md:mx-6 md:mb-6">
            {{ config('enterprise-ui.app_name', config('app.name')) }}
        </footer>
    </div>
</body>
</html>
