@hasrole('Admin_Super')
<x-nav-link :href="route('index')">
    General
</x-nav-link>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle bg-transparent font-weight-bold" href="#" id="navbarDropdown"
        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Monitoring
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item bg-transparent text-dark" href="{{ route('health') }}">
            <i class="fa fa-medkit" aria-hidden="true"></i> {{ __('health::notifications.laravel_health') }}
        </a>
        <a class="dropdown-item bg-transparent text-dark" href="{{ route('horizon.index') }}">
            <i class="fa fa-eercast" aria-hidden="true"></i> Horizon
        </a>
        <a class="dropdown-item bg-transparent text-dark" href="{{ route('telescope') }}">
            <i class="fa fa-star" aria-hidden="true"></i> Telescope
        </a>
        <a class="dropdown-item bg-transparent text-dark" href="/log-viewer">
            <i class="fa fa-eye" aria-hidden="true"></i> Log Viewer
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item bg-transparent text-dark" href="{{ config('jenkins.host') }}">
            Jenkins
        </a>
    </div>
@endhasrole
