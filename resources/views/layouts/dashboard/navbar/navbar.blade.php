<div class="container">
    <nav class="navbar navbar-expand-sm shadow navbar-dark rounded-bottom border-bottom border-left border-right border-dark">
        @include('layouts.dashboard.navbar.navbar-brand')
        @include('layouts.dashboard.navbar.navbar-toggler')

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto nav-pills">
                @include('layouts.dashboard.navbar.user-links')
                @include('layouts.dashboard.navbar.workspace-user-links')
                @include('layouts.dashboard.navbar.superadmin-links')
            </ul>
            <ul class="navbar-nav ms-auto">
                @can('view workspace')
                    <li class="nav-item">
                        <x-nav-link :href="route('workspace_settings')">
                            <span tabindex="0" data-toggle="tooltip" title="Workspace Settings">
                                <i class="fa fa-users fa-lg" aria-hidden="true"></i>
                                <span class="d-sm-none">Workspace Settings</span>
                            </span>
                        </x-nav-link>
                    </li>
                @endcan
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle font-weight-bold text-white"
                        href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user-circle-o fa-lg" aria-hidden="true"></i>
                        <span class="d-sm-none">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="text-left dropdown-item" href="{{ route('dashboard.profile') }}">
                                <i class="fa fa-user-o fa-lg" aria-hidden="true"></i> Profile
                            </a>
                            @hasrole('Admin_Super')
                                <div class="dropdown-divider"></div>
                                <a class="text-left dropdown-item" href="/health">
                                    <i class="fa fa-medkit" aria-hidden="true"></i> {{ __('health::notifications.laravel_health') }}
                                </a>
                                <a class="text-left dropdown-item" href="{{ route('telescope') }}">
                                    <i class="fa fa-server" aria-hidden="true"></i> Telescope
                                </a>
                            @endrole
                            <a class="text-left dropdown-item "
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
</div>
