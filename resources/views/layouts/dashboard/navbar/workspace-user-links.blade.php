@hasanyrole('User_Workspace|Admin_Workspace')
    @can('view apps')
        <li class="nav-item">
            <x-nav-link :href="route('index')">
                Apps
            </x-nav-link>
        </li>
    @endcan
    @can('create app')
        <li class="nav-item">
            <x-nav-link :href="route('add_app_info')">
                Create App
            </x-nav-link>
        </li>
    @endcan
    @can('create bundle')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="navbarDropdown"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                AppStore
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item bg-transparent text-dark" href="/dashboard/create-bundle">
                    <i class="fa fa-apple" aria-hidden="true"></i> Bundle ID
                </a>
            </div>
        </li>
    @endcan
    @can('create bundle')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="navbarDropdown"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                GooglePlay
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item bg-transparent text-dark" href="https://play.google.com/console/" target="_blank">
                    <i class="fa fa-google" aria-hidden="true"></i> Console
                </a>
            </div>
        </li>
    @endcan
    @can('view workspace')
        <li class="nav-item">
            <x-nav-link :href="route('workspace_settings')">
                Workspace Settings
            </x-nav-link>
        </li>
    @endcan
@endhasanyrole
