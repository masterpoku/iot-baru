<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="../../../html/ltr/vertical-menu-template/index.html"><span class="brand-logo">
                        <h2 class="brand-text">Get Plastic</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('dashboard') }}"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span>
                </a>
            </li>
            @role('admin')
            <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('user.index') }}"><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Dashboards">Users</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('permissions*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('permission.create') }}"><i data-feather='unlock'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Permission</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('sensors*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('monitoring.index') }}"><i data-feather='settings'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Monitoring</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('roles*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('roles.index') }}"><i data-feather='settings'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Permission user</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('device*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('device.index') }}"><i data-feather='settings'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Device</span>
                </a>
            </li>
            @endrole
        </ul>
    </div>
</div>