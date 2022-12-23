@hasrole('User')
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
@endhasrole
