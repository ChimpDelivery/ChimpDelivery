@hasrole('User')
    @can('create workspace')
        <li class="nav-item">
            <x-nav-link :href="route('index')">
                Create Workspace
            </x-nav-link>
        </li>
    @endcan
    @can('join workspace')
        <li class="nav-item">
            <x-nav-link :href="route('workspace_join')">
                Join Workspace
            </x-nav-link>
        </li>
    @endcan
@endhasrole
