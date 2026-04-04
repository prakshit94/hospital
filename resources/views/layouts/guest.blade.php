<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? config('enterprise-ui.app_name', config('app.name')) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-background text-foreground">
    <div class="absolute inset-0 z-0 overflow-hidden bg-background">
        <div class="premium-grid absolute inset-0 opacity-70"></div>
        <div class="absolute top-[-10%] left-[-10%] h-[40%] w-[40%] rounded-full bg-primary/14 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] h-[40%] w-[40%] rounded-full bg-sky-500/14 blur-[120px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.4),transparent_34%)] dark:bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.06),transparent_24%)]"></div>
    </div>

    <div class="relative z-10 min-h-full">
        @yield('content')
    </div>
</body>
</html>
