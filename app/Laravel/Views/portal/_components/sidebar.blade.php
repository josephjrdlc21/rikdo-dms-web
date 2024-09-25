<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="p-2">
            <img src="{{asset('assets/img/rikdo-logo.png')}}" alt="logo" width="100" class="img-fluid mx-auto d-block rounded-circle">
        </div>
        <div class="sidebar-brand">
            <a href="{{route('portal.index')}}">RIKDO DMS</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{route('portal.index')}}">RD</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Quick Access</li>
            <li class="{{request()->segment(1) != "" ? "" : "active"}}">
                <a class="nav-link" href="{{route('portal.index')}}"><i class="fas fa-signal"></i> <span>Dashboard</span></a>
            </li>
            <li class="menu-header">Menus</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i> <span>User Applications</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="#">Pending</a></li>
                    <li><a class="nav-link" href="#">Approved</a></li>
                    <li><a class="nav-link" href="#">Rejected</a></li>
                </ul>
            </li>
            <li><a class="nav-link" href="#"><i class="fas fa-users-cog"></i> <span>Account Management</span></a></li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i> <span>My Research</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="#">Pending</a></li>
                    <li><a class="nav-link" href="#">Approved</a></li>
                    <li><a class="nav-link" href="#">Revision</a></li>
                    <li><a class="nav-link" href="#">Rejected</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Student Research</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="#">Pending</a></li>
                    <li><a class="nav-link" href="#">Approved</a></li>
                    <li><a class="nav-link" href="#">Revision</a></li>
                    <li><a class="nav-link" href="#">Rejected</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder"></i> <span>All Research</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="#">Pending</a></li>
                    <li><a class="nav-link" href="#">Approved</a></li>
                    <li><a class="nav-link" href="#">Revision</a></li>
                    <li><a class="nav-link" href="#">Rejected</a></li>
                </ul>
            </li>            
            <li><a class="nav-link" href="#"><i class="fas fa-copy"></i> <span>Completed Research</span></a></li>
            <li><a class="nav-link" href="#"><i class="fas fa-globe"></i> <span>Posted Research</span></a></li>
            <li><a class="nav-link" href="#"><i class="fas fa-archive"></i> <span>Archives</span></a></li>
            <li><a class="nav-link" href="#"><i class="fas fa-file-invoice"></i> <span>Research Reports</span></a></li>
            <li class="menu-header">System Settings</li>
            <li class="dropdown {{request()->segment(1) == "cms" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>Content Management</span></a>
                <ul class="dropdown-menu">
                    <li class="{{request()->segment(2) == "roles" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.roles.index')}}">Roles</a></li>
                    <li class="{{request()->segment(2) == "permissions" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.permissions.index')}}">Permissions</a></li>
                </ul>
            </li>
            <li><a class="nav-link" href="#"><i class="fas fa-compass"></i> <span>Audit Trail</span></a></li>
        </ul>    
    </aside>
</div>