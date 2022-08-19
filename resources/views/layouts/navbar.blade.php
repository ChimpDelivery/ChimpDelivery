<div class="container-fluid">
    <nav class="navbar navbar-expand-sm navbar-light bg-light shadow-sm">
        <a class="navbar-brand font-weight-bold" href="/dashboard">
            <img src="{{ asset('Talus_icon.ico') }}" alt="..." height="36" />
            @hasrole('User')
                {{ config('app.name') }}
            @else
                <span class="text-capitalize font-weight-bold text-dark">{{ Auth::user()->workspace->name }}</span>
            @endrole
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto nav-pills">
                @can('create workspace')
                <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard">Create Workspace</a>
                </li>
                @endcan
                @can('join workspace')
                <li class="nav-item {{ (request()->is('dashboard/join-workspace')) ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard/join-workspace">Join Workspace</a>
                </li>
                @endcan

                @can('view app')
                <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard">Apps</a>
                </li>
                @endcan
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
                @can('scan jobs')
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="/dashboard/scan-repo">Scan Github</a>
                </li>
                @endcan

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
                                <img src="{{ asset('Talus_icon.ico') }}" alt="..." width=24 height=24 /> {{ config('app.name') }}
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
