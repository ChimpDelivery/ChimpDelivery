<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- favicon_begin !-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <!-- favicon_end !-->
    <!-- bootstrap_begin !-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- bootstrap_end !-->
    <style>
        .toast-container { position: fixed; right: 20px; top: 20px; }
        .toast:not(.showing):not(.show) { display: none !important; }
        .popover-inner { width: 250px !important; max-width: 250px !important; }
        .popover { width: 250px !important; max-width: 250px !important; }
    </style>
</head>
<body>
    <!-- navbar_begin !-->
    @include('layouts.navbar')
    <!-- navbar_end !-->

    <!-- errors_begin !-->
    @include('layouts.error')
    <!-- errors_end !-->

    <!-- contents_start !-->
    <section class="page-content">
        @yield('content')
    </section>
    <!-- contents_end !-->

    <!-- toast_begin !-->
    @include('layouts.notification-toast')
    <!-- toast_end !-->
</body>

<section class="scripts">
    <script type="text/javascript">
        $(document).ready(function() {
            console.log("talus-toast-cookie:" + getCookie('talus-toast-cookie'));

            if (!getCookie('talus-toast-cookie')) {
                $('#toast-talus').toast('show');
            }

            $('#toast-flash').toast('show');
        });

        function setToastCookie() {
            setCookie('talus-toast-cookie', 1, 1);
        }

        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function expireCookie(name) {
            document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
    </script>

    @yield('scripts')
</section>
