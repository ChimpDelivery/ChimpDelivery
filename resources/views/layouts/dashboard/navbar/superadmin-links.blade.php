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
        <a class="dropdown-item bg-transparent text-dark" href="{{ route('laralens.index') }}">
            <i class="fa-solid fa-server" aria-hidden="true"></i> LaraLens
        </a>
        <a class="dropdown-item bg-transparent text-dark" href="{{ route('health') }}">
            <i class="fa-solid fa-laptop-medical" aria-hidden="true"></i> {{ __('health::notifications.laravel_health') }}
        </a>
        <a class="dropdown-item bg-transparent text-dark" href="{{ route('horizon.index') }}">
            <i class="{{ config('icons.laravel') }}" aria-hidden="true"></i> Horizon
        </a>
        <a class="dropdown-item bg-transparent text-dark" href="{{ route('telescope') }}">
            <i class="{{ config('icons.laravel') }}" aria-hidden="true"></i> Telescope
        </a>
        <a class="dropdown-item bg-transparent text-dark" href="/log-viewer">
            <i class="fa-solid fa-eye" aria-hidden="true"></i> Log Viewer
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item bg-transparent text-dark" href="{{ config('jenkins.host') }}">
            <i class="{{ config('icons.jenkins') }}" aria-hidden="true"></i> Jenkins
        </a>
    </div>
@endhasrole
