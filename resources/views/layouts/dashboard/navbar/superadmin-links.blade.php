@hasrole('Admin_Super')
    <li class="nav-item {{ request()->is('dashboard/settings') ? 'active' : '' }}">
        <a class="nav-link font-weight-bold" href="/dashboard/settings">Dashboard Settings</a>
    </li>
@endhasrole
