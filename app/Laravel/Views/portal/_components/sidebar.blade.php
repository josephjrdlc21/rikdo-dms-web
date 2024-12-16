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
            @if($auth->canAny(['portal.users_kyc.index', 'portal.users_kyc.approved', 'portal.users_kyc.rejected'], 'web'))         
            <li class="dropdown {{request()->segment(1) == "users-kyc" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i> <span>User Applications</span></a>
                <ul class="dropdown-menu">
                    @if($auth->canAny(['portal.users_kyc.index'], 'web'))         
                    <li class="{{request()->segment(1) == "users-kyc" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.users_kyc.index')}}">Pending</a></li>
                    @endif
                    @if($auth->canAny(['portal.users_kyc.approved'], 'web'))         
                    <li class="{{request()->segment(1) == "users-kyc" && request()->segment(2) == "approved" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.users_kyc.approved')}}">Approved</a></li>
                    @endif
                    @if($auth->canAny(['portal.users_kyc.rejected'], 'web'))         
                    <li class="{{request()->segment(1) == "users-kyc" && request()->segment(2) == "rejected" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.users_kyc.rejected')}}">Rejected</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if($auth->canAny(['portal.users.index'], 'web'))
            <li class="{{request()->segment(1) == "users" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.users.index')}}"><i class="fas fa-users-cog"></i> <span>Account Management</span></a></li>
            @endif
            @if($auth->canAny(['portal.research.index', 'portal.research.approved', 'portal.research.for_revision', 'portal.research.rejected'], 'web'))
            <li class="dropdown {{request()->segment(1) == "research" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file"></i> <span>My Research</span></a>
                <ul class="dropdown-menu">
                    @if($auth->canAny(['portal.research.index'], 'web'))         
                    <li class="{{request()->segment(1) == "research" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research.index')}}">Pending</a></li>
                    @endif
                    @if($auth->canAny(['portal.research.approved'], 'web'))         
                    <li class="{{request()->segment(1) == "research" && request()->segment(2) == "approved" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research.approved')}}">Approved</a></li>
                    @endif
                    @if($auth->canAny(['portal.research.for_revision'], 'web'))         
                    <li class="{{request()->segment(1) == "research" && request()->segment(2) == "for-revision" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research.for_revision')}}">Revision</a></li>
                    @endif
                    @if($auth->canAny(['portal.research.rejected'], 'web'))         
                    <li class="{{request()->segment(1) == "research" && request()->segment(2) == "rejected" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research.rejected')}}">Rejected</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if($auth->canAny(['portal.student_research.index', 'portal.student_research.approved', 'portal.student_research.for_revision', 'portal.student_research.rejected'], 'web'))
            <li class="dropdown {{request()->segment(1) == "student-research" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Student Research</span></a>
                <ul class="dropdown-menu">
                    @if($auth->canAny(['portal.student_research.index'], 'web'))         
                    <li class="{{request()->segment(1) == "student-research" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.student_research.index')}}">Pending</a></li>
                    @endif
                    @if($auth->canAny(['portal.student_research.approved'], 'web'))         
                    <li class="{{request()->segment(1) == "student-research" && request()->segment(2) == "approved" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.student_research.approved')}}">Approved</a></li>
                    @endif
                    @if($auth->canAny(['portal.student_research.for_revision'], 'web'))         
                    <li class="{{request()->segment(1) == "student-research" && request()->segment(2) == "for-revision" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.student_research.for_revision')}}">Revision</a></li>
                    @endif
                    @if($auth->canAny(['portal.student_research.rejected'], 'web'))         
                    <li class="{{request()->segment(1) == "student-research" && request()->segment(2) == "rejected" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.student_research.rejected')}}">Rejected</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if($auth->canAny(['portal.all_research.index', 'portal.all_research.approved', 'portal.all_research.for_revision', 'portal.all_research.rejected'], 'web'))
            <li class="dropdown {{request()->segment(1) == "all-research" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder"></i> <span>All Research</span></a>
                <ul class="dropdown-menu">
                    @if($auth->canAny(['portal.all_research.index'], 'web'))         
                    <li class="{{request()->segment(1) == "all-research" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.all_research.index')}}">Pending</a></li>
                    @endif
                    @if($auth->canAny(['portal.all_research.approved'], 'web'))
                    <li class="{{request()->segment(1) == "all-research" && request()->segment(2) == "approved" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.all_research.approved')}}">Approved</a></li>
                    @endif
                    @if($auth->canAny(['portal.all_research.for_revision'], 'web'))
                    <li class="{{request()->segment(1) == "all-research" && request()->segment(2) == "for-revision" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.all_research.for_revision')}}">Revision</a></li>
                    @endif
                    @if($auth->canAny(['portal.all_research.rejected'], 'web'))
                    <li class="{{request()->segment(1) == "all-research" && request()->segment(2) == "rejected" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.all_research.rejected')}}">Rejected</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if($auth->canAny(['portal.completed_research.index'], 'web'))         
            <li class="{{request()->segment(1) == "completed-research" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.completed_research.index')}}"><i class="fas fa-copy"></i> <span>Completed Research</span></a></li>
            @endif
            @if($auth->canAny(['portal.posted_research.index'], 'web'))         
            <li class="{{request()->segment(1) == "posted-research" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.posted_research.index')}}"><i class="fas fa-globe"></i> <span>Posted Research</span></a></li>
            @endif
            @if($auth->canAny(['portal.archives.index', 'portal.archives.completed'], 'web'))
            <li class="dropdown {{request()->segment(1) == "archives" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-archive"></i> <span>Archives</span></a>
                <ul class="dropdown-menu">
                    @if($auth->canAny(['portal.archives.index'], 'web'))
                    <li class="{{request()->segment(1) == "archives" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.archives.index')}}">Researches</a></li>
                    @endif
                    @if($auth->canAny(['portal.archives.completed'], 'web'))
                    <li class="{{request()->segment(1) == "archives" && request()->segment(2) == "completed" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.archives.completed')}}">Completed</a></li>
                    @endif
                </ul>
            </li>
            @endif
            @if($auth->canAny(['portal.research_reports.index', 'portal.research_reports.completed', 'portal.research_reports.posted', 'portal.research_reports.summary'], 'web'))
            <li class="dropdown {{request()->segment(1) == "research-reports" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-invoice"></i> <span>Research Reports</span></a>
                <ul class="dropdown-menu">
                    @if($auth->canAny(['portal.research_reports.index'], 'web'))
                    <li class="{{request()->segment(1) == "research-reports" && request()->segment(2) == "" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research_reports.index')}}">Researches</a></li>
                    @endif
                    @if($auth->canAny(['portal.research_reports.completed'], 'web'))
                    <li class="{{request()->segment(1) == "research-reports" && request()->segment(2) == "completed" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research_reports.completed')}}">Completed</a></li>
                    @endif
                    @if($auth->canAny(['portal.research_reports.posted'], 'web'))
                    <li class="{{request()->segment(1) == "research-reports" && request()->segment(2) == "posted" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research_reports.posted')}}">Posted</a></li>
                    @endif
                    @if($auth->canAny(['portal.research_reports.summary'], 'web'))
                    <li class="{{request()->segment(1) == "research-reports" && request()->segment(2) == "summary" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.research_reports.summary')}}">Summary</a></li>
                    @endif
                </ul>
            </li>
            @endif
            <li class="menu-header">System Settings</li>
            @if($auth->canAny(['portal.cms.roles.index', 'portal.cms.permissions.index', 'portal.cms.departments.index', 'portal.cms.permissions.index', 'portal.cms.courses.index', 
            'portal.cms.yearlevels.index', 'portal.cms.research_types.index', 'portal.cms.pages.index', 'portal.audit_trail.index', 'portal.notifications.index'], 'web'))
            <li class="dropdown {{request()->segment(1) == "cms" ? "active" : ""}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>Content Management</span></a>
                <ul class="dropdown-menu">
                    @if($auth->canAny(['portal.cms.roles.index'], 'web'))
                    <li class="{{request()->segment(2) == "roles" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.roles.index')}}">Roles</a></li>
                    @endif
                    @if($auth->canAny(['portal.cms.permissions.index'], 'web'))
                    <li class="{{request()->segment(2) == "permissions" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.permissions.index')}}">Permissions</a></li>
                    @endif
                    @if($auth->canAny(['portal.cms.departments.index'], 'web'))
                    <li class="{{request()->segment(2) == "departments" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.departments.index')}}">Departments</a></li>
                    @endif
                    @if($auth->canAny(['portal.cms.courses.index'], 'web'))
                    <li class="{{request()->segment(2) == "courses" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.courses.index')}}">Courses</a></li>
                    @endif
                    @if($auth->canAny(['portal.cms.yearlevels.index'], 'web'))
                    <li class="{{request()->segment(2) == "yearlevels" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.yearlevels.index')}}">Yearlevels</a></li>
                    @endif
                    @if($auth->canAny(['portal.cms.research_types.index'], 'web'))
                    <li class="{{request()->segment(2) == "research-types" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.research_types.index')}}">Research Types</a></li>
                    @endif
                    @if($auth->canAny(['portal.cms.pages.index'], 'web'))
                    <li class="{{request()->segment(2) == "pages" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.cms.pages.index')}}">Pages</a></li>
                    @endif
                </ul>
            </li>
            @if($auth->canAny(['portal.audit_trail.index'], 'web'))
            <li class="{{request()->segment(1) == "audit-trail" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.audit_trail.index')}}"><i class="fas fa-compass"></i> <span>Audit Trail</span></a></li>
            @endif
            @if($auth->canAny(['portal.notifications.index'], 'web'))
            <li class="{{request()->segment(1) == "notifications" ? "active" : ""}}"><a class="nav-link" href="{{route('portal.notifications.index')}}"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
            @endif
            @endif
        </ul>    
    </aside>
</div>