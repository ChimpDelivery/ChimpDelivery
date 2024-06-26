@hasanyrole('User_Workspace|Admin_Workspace')
    @can('view apps')
        <li class="nav-item">
            <x-nav-link :href="route('index')">
                Apps
            </x-nav-link>
        </li>
    @endcan
    @can('create bundle')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle bg-transparent font-weight-bold" href="#" id="navbarDropdown"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Apple
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item bg-transparent text-dark" href="/dashboard/create-bundle">
                    <i class="{{ config('icons.apple') }}" aria-hidden="true"></i> Bundle ID
                </a>
            </div>
        </li>
    @endcan
    @can('create bundle')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle bg-transparent font-weight-bold" href="#" id="navbarDropdown"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Google
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item bg-transparent text-dark" href="https://play.google.com/console/" target="_blank">
                    <i class="{{ config('icons.google') }}" aria-hidden="true"></i> Play Console
                </a>
            </div>
        </li>
    @endcan
@endhasanyrole
