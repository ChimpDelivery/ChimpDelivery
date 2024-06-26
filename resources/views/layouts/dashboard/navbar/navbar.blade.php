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
                                <i class="fa-solid fa-users" aria-hidden="true"></i>
                                <span class="d-sm-none">
                                    {{ Auth::user()->workspace->name }}
                                </span>
                            </span>
                        </x-nav-link>
                    </li>
                @endcan
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle font-weight-bold text-white"
                        href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle" alt="" style="margin-bottom: 4px;" width="18" height="18" src="{{ Auth::user()->gravatar }}" />

                        <span class="d-sm-none">
                            {{ Auth::user()->name }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="text-left dropdown-item" href="{{ route('dashboard.profile') }}">
                                <i class="fa-solid fa-user" aria-hidden="true"></i> &nbsp; Profile
                            </a>
                            <a class="text-left dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fa-solid fa-sign-out"></i> &nbsp; {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
