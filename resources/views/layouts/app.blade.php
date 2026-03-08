<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name') }}</title>
        <meta
            name="description"
            content="HSYN Nexus; musteri, hizmet, tahsilat, destek ve sunucu gozetimini tek merkezde toplayan premium operasyon paneli."
        >
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap"
            rel="stylesheet"
        >
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="site-body {{ $bodyClass ?? '' }}">
        @if (session('status'))
            <div class="flash-banner">
                <div class="flash-banner__inner">{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="flash-banner">
                <div class="flash-banner__inner">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        @yield('content')
    </body>
</html>
