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
        .toast-container {
            position: fixed;
            right: 20px;
            top: 20px;
        }

        .toast:not(.showing):not(.show) {
            display: none !important;
        }

        .popover-inner {
            width: 250px !important;
            max-width: 250px !important;
        }

        .popover {
            width: 250px !important;
            max-width: 250px !important;
        }
    </style>
</head>
<body>
    <!-- navbar_begin !-->
    <div class="container-fluid">
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <a class="navbar-brand font-weight-bold" href="/dashboard">
                <img src="{{ asset('Talus_icon.ico') }}" alt="..." height="36" />
                Dashboard
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto nav-pills">
                    <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                        <a class="nav-link font-weight-bold" href="/dashboard">Apps <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item {{ (request()->is('dashboard/create-bundle')) ? 'active' : '' }}">
                        <a class="nav-link font-weight-bold" href="/dashboard/create-bundle">Create Bundle</a>
                    </li>
                    <li class="nav-item {{ (request()->is('dashboard/add-app-info')) ? 'active' : '' }}">
                        <a class="nav-link font-weight-bold" href="/dashboard/add-app-info">Create App</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" href="/dashboard/scan-repo">Scan Github</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle font-weight-bold font-italic text-muted" href="#" role="button" id="dropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Hi, {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu bg-primary dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="#">
                                    <img src="{{ asset('Talus_icon.ico') }}" alt="..." width=24 height=24 /> {{ __('Talus Workspace') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="/health">
                                    <i class="fa fa-medkit" aria-hidden="true"></i> {{ __('Dashboard Health') }}
                                </a>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="/dashboard/clear-cache">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> {{ __('Clear Cache') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="https://trello.com/b/U6FsYqMR/kanban-template">
                                    <i class="fa fa-trello fa-lg text-white" aria-hidden="true"></i> {{ __('Trello') }}
                                </a>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="https://drive.google.com/drive/folders/1HiDzlKwkSWMF9sk22pqwoG058933hqwa?usp=sharing">
                                    <i class="fa fa-google fa-lg text-white" aria-hidden="true"></i> {{ __('Google Drive') }}
                                </a>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="https://github.com/talusstudio">
                                    <i class="fa fa-github fa-lg text-white" aria-hidden="true"></i> {{ __('Github') }}
                                </a>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="https://appstoreconnect.apple.com/apps">
                                    <i class="fa fa-apple fa-lg text-white" aria-hidden="true"></i> {{ __('Appstore Connect') }}
                                </a>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="https://developers.facebook.com">
                                    <i class="fa fa-facebook fa-lg text-white" aria-hidden="true"></i> {{ __('Facebook Dashboard') }}
                                </a>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="https://go.gameanalytics.com/login">
                                    <i class="fa fa-pie-chart text-white" aria-hidden="true"></i> {{ __('GA Dashboard') }}
                                </a>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="http://webmail.talusstudio.com">
                                    <i class="fa fa-envelope text-white" aria-hidden="true"></i> {{ __('Webmail') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="active text-left dropdown-item text-white font-weight-bold" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fa fa-sign-out fa-lg text-white"></i> {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- navbar_end !-->

    <!-- errors_begin !-->
    <div class="container-fluid">
        @if($errors->any())
        <br />
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <!-- errors_end !-->

    <!-- contents_start !-->
    <section class="page-content">
        @yield('content')
    </section>
    <!-- contents_end !-->

    <!-- toast_begin !-->
    <div class="toast-container">
        <div id="toast-talus" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
            <div class="toast-header bg-success">
                <img src="{{ asset('Talus_icon.ico') }}" class="rounded mr-2" alt="..." width="16" height="16">
                <strong class="mr-auto text-white">Talus Dashboard</strong>
                <small class="text-white">7 mins ago</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" onclick="setToastCookie()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Welcome to Talus Dashboard, have fun :) Thanks for all feedbacks and have a nice day.
            </div>
        </div>

        @if (session()->has('success'))
        <div id="toast-flash" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
            <div class="toast-header bg-success">
                <img src="{{ asset('Talus_icon.ico') }}" class="rounded mr-2" alt="..." width="16" height="16">
                <strong class="mr-auto text-white">Notification</strong>
                <small class="text-white">1 mins ago</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
        @endif
    </div>
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
