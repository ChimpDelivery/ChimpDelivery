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

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        @env('local')
        <script type="text/javascript">
            function callbackThen(response)
            {
                console.log(response.status);
                response.json().then(function(data)
                {
                    console.log(data);
                });
            }

            function callbackCatch(error)
            {
                console.error('Error:', error)
            }
        </script>
        @endenv

        @env('local')
        {!! htmlScriptTagJsApi([
            'callback_then' => 'callbackThen',
            'callback_catch' => 'callbackCatch'
        ]) !!}
        @endenv
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
    </body>
</html>
