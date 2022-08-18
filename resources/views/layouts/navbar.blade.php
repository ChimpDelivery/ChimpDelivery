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
                @can('create app')
                <li class="nav-item {{ (request()->is('dashboard/add-app-info')) ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard/add-app-info">Create App</a>
                </li>
                @endcan
                @can('create bundle')
                <li class="nav-item {{ (request()->is('dashboard/create-bundle')) ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard/create-bundle">iOS Bundle</a>
                </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="/dashboard/scan-repo">Scan Github</a>
                </li>
                @can('view workspace')
                <li class="nav-item {{ (request()->is('dashboard/ws-settings')) ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard/workspace-settings">Workspace Settings</a>
                </li>
                @endcan
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
                                <i class="fa fa-medkit" aria-hidden="true"></i> {{ __('health::notifications.laravel_health') }}
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
