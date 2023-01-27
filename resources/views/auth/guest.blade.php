<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        {!! htmlScriptTagJsApi() !!}

        <style>
            .divider { display: flex; align-items: center; padding: 0 1rem; }
            .divider:before, .divider:after { content: ''; flex: 0 1 100%; border-bottom: 5px dotted #ccc; margin: 0 1rem; }
            .divider:before { margin-left: 0; }
            .divider:after { margin-right: 0; }
            .divider[text-position="right"]:after, .divider[text-position="left"]:before { content: none; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="bg-gray-100 dark:bg-gray-900">
            <div class="w-full bg-white dark:bg-gray-800 shadow-md overflow-hidden">
                {{ $slot }}
            </div>
            @include('cookie-consent::index')
        </div>
    </body>
</html>
