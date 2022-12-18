<nav class="navbar navbar-expand-sm shadow navbar-dark rounded-bottom border-bottom border-dark">
    <a class="navbar-brand font-weight-bold" href="/dashboard">
        <img src="{{ asset('Talus_icon.ico') }}" alt="" width="30" height="30"/>
        @hasrole('User')
            {{ config('app.name') }}
        @else
            <span class="text-capitalize font-weight-bold">{{ Auth::user()->workspace->name }}</span>
        @endrole
    </a>

    <button class="navbar-toggler border-0" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto nav-pills">
            @can('create workspace')
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="/dashboard">Create Workspace</a>
                </li>
            @endcan
            @can('join workspace')
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="/dashboard/workspace-join">Join Workspace</a>
                </li>
            @endcan
            @can('view apps')
                <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard">Apps</a>
                </li>
            @endcan
            @can('create app')
                <li class="nav-item {{ request()->is('dashboard/add-app-info') ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard/add-app-info">Create App</a>
                </li>
            @endcan
            @can('create bundle')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle font-weight-bold bg-transparent" href="#" id="navbarDropdown"
                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        AppStore
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/dashboard/create-bundle">Bundle ID</a>
                    </div>
                </li>
            @endcan
            @can('create bundle')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle font-weight-bold bg-transparent" href="#" id="navbarDropdown"
                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        GooglePlay
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="https://play.google.com/console/" target="_blank">Console</a>
                    </div>
                </li>
            @endcan
            @can('view workspace')
                <li class="nav-item {{ request()->is('dashboard/workspace-settings') ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard/workspace-settings">Workspace Settings</a>
                </li>
            @endcan
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle font-weight-bold font-italic text-white" href="#" role="button"
                   id="dropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    <i class="fa fa-user-circle-o fa-lg" aria-hidden="true"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="text-left dropdown-item text-secondary font-weight-bold bg-white"
                           href="{{ route('dashboard.profile') }}">
                            <i class="fa fa-address-book-o fa-lg" aria-hidden="true"></i> Profile
                        </a>
                        @hasrole('Admin_Super')
                        <div class="dropdown-divider"></div>
                        <a class="text-left dropdown-item text-secondary font-weight-bold bg-white" href="/health">
                            <i class="fa fa-medkit"
                               aria-hidden="true"></i> {{ __('health::notifications.laravel_health') }}
                        </a>
                        <a class="text-left dropdown-item text-secondary font-weight-bold bg-white"
                           href="{{ route('telescope') }}">
                            <i class="fa fa-server" aria-hidden="true"></i> Telescope
                        </a>
                        @endrole
                        <a class="text-left dropdown-item text-secondary font-weight-bold bg-white"
                           href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fa fa-sign-out fa-lg"></i> {{ __('Log Out') }}
                        </a>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
