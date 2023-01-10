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

        <script type="text/javascript">
            function callbackThen(response)
            {
                //console.log(response.status);
                response.json().then(function(data)
                {
                    //console.log(data);
                });
            }

            function callbackCatch(error)
            {
                //console.error('Error:', error)
            }
        </script>

        {!! htmlScriptTagJsApi([
            'callback_then' => 'callbackThen',
            'callback_catch' => 'callbackCatch'
        ]) !!}
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
