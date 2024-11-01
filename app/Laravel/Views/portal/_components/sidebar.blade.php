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
            <li class="dropdown {{request()->segment(1) == "users-kyc" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i> <span>User Applications</span></a>
                <ul class="dropdown-menu">
                    <li class="{{request()->segment(1) == "users-kyc" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.users_kyc.index')}}">Pending</a></li>
                    <li class="{{request()->segment(1) == "users-kyc" && request()->segment(2) == "approved" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.users_kyc.approved')}}">Approved</a></li>
                    <li class="{{request()->segment(1) == "users-kyc" && request()->segment(2) == "rejected" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.users_kyc.rejected')}}">Rejected</a></li>
                </ul>
            </li>
            <li class="{{request()->segment(1) == "users" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.users.index')}}"><i class="fas fa-users-cog"></i> <span>Account Management</span></a></li>
            <li class="dropdown {{request()->segment(1) == "research" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i> <span>My Research</span></a>
                <ul class="dropdown-menu">
                    <li class="{{request()->segment(1) == "research" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research.index')}}">Pending</a></li>
                    <li class="{{request()->segment(1) == "research" && request()->segment(2) == "approved" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research.approved')}}">Approved</a></li>
                    <li class="{{request()->segment(1) == "research" && request()->segment(2) == "for-revision" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research.for_revision')}}">Revision</a></li>
                    <li class="{{request()->segment(1) == "research" && request()->segment(2) == "rejected" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research.rejected')}}">Rejected</a></li>
                </ul>
            </li>
            <li class="dropdown {{request()->segment(1) == "student-research" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Student Research</span></a>
                <ul class="dropdown-menu">
                    <li class="{{request()->segment(1) == "student-research" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.student_research.index')}}">Pending</a></li>
                    <li class="{{request()->segment(1) == "student-research" && request()->segment(2) == "approved" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.student_research.approved')}}">Approved</a></li>
                    <li class="{{request()->segment(1) == "student-research" && request()->segment(2) == "for-revision" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.student_research.for_revision')}}">Revision</a></li>
                    <li class="{{request()->segment(1) == "student-research" && request()->segment(2) == "rejected" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.student_research.rejected')}}">Rejected</a></li>
                </ul>
            </li>
            <li class="dropdown {{request()->segment(1) == "all-research" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder"></i> <span>All Research</span></a>
                <ul class="dropdown-menu">
                    <li class="{{request()->segment(1) == "all-research" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.all_research.index')}}">Pending</a></li>
                    <li class="{{request()->segment(1) == "all-research" && request()->segment(2) == "approved" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.all_research.approved')}}">Approved</a></li>
                    <li class="{{request()->segment(1) == "all-research" && request()->segment(2) == "for-revision" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.all_research.for_revision')}}">Revision</a></li>
                    <li class="{{request()->segment(1) == "all-research" && request()->segment(2) == "rejected" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.all_research.rejected')}}">Rejected</a></li>
                </ul>
            </li>            
            <li class="{{request()->segment(1) == "completed-research" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.completed_research.index')}}"><i class="fas fa-copy"></i> <span>Completed Research</span></a></li>
            <li class="{{request()->segment(1) == "posted-research" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.posted_research.index')}}"><i class="fas fa-globe"></i> <span>Posted Research</span></a></li>
            <li class="dropdown {{request()->segment(1) == "archives" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-archive"></i> <span>Archives</span></a>
                <ul class="dropdown-menu">
                    <li class="{{request()->segment(1) == "archives" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.archives.index')}}">Researches</a></li>
                    <li class="{{request()->segment(1) == "archives" && request()->segment(2) == "completed" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.archives.completed')}}">Completed</a></li>
                </ul>
            </li>
            <li class="dropdown {{request()->segment(1) == "research-reports" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-invoice"></i> <span>Research Reports</span></a>
                <ul class="dropdown-menu">
                    <li class="{{request()->segment(1) == "research-reports" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research_reports.index')}}">Researches</a></li>
                    <li class="{{request()->segment(1) == "research-reports" && request()->segment(2) == "completed" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research_reports.completed')}}">Completed</a></li>
                    <li class="{{request()->segment(1) == "research-reports" && request()->segment(2) == "posted" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research_reports.posted')}}">Posted</a></li>
                </ul>
            </li>
            <li class="menu-header">System Settings</li>
            <li class="dropdown {{request()->segment(1) == "cms" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>Content Management</span></a>
                <ul class="dropdown-menu">
                    <li class="{{request()->segment(2) == "roles" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.roles.index')}}">Roles</a></li>
                    <li class="{{request()->segment(2) == "permissions" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.permissions.index')}}">Permissions</a></li>
                    <li class="{{request()->segment(2) == "departments" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.departments.index')}}">Departments</a></li>
                    <li class="{{request()->segment(2) == "courses" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.courses.index')}}">Courses</a></li>
                    <li class="{{request()->segment(2) == "yearlevels" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.yearlevels.index')}}">Yearlevels</a></li>
                    <li class="{{request()->segment(2) == "research-types" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.research_types.index')}}">Research Types</a></li>
                </ul>
            </li>
            <li><a class="nav-link" href="#"><i class="fas fa-compass"></i> <span>Audit Trail</span></a></li>
        </ul>    
    </aside>
</div>